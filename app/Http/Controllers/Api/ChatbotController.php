<?php

namespace App\Http\Controllers\Api; // Solo un namespace aquí

use App\Http\Controllers\Controller;
use App\Models\AiQuery;
use App\Models\ChatbotConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Enviar un mensaje al chatbot (Gemini 3 Flash)
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message'         => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer',
            'course_id'       => 'nullable|integer',
        ]);

        try {
            $userMessage = $request->message;
            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                return response()->json(['success' => false, 'error' => 'Configuración incompleta: Falta API Key.'], 500);
            }

            // 1. Llamada a la API de Gemini (Corregida la sintaxis del POST)
            // Usamos Gemini 3 Flash según los estándares de 2026
            $response = Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $userMessage]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $botReply = $response->json('candidates.0.content.parts.0.text');

                if (!$botReply) {
                    return response()->json(['success' => false, 'error' => 'Respuesta vacía de la IA.'], 500);
                }

                // 2. Lógica de Conversación (Para que getHistory funcione)
                return DB::transaction(function () use ($request, $userMessage, $botReply) {
                    
                    // Si no hay ID, creamos una conversación nueva
                    $conversationId = $request->conversation_id;
                    if (!$conversationId) {
                        $newConv = ChatbotConversation::create([
                            'user_id' => Auth::id(),
                            'course_id' => $request->course_id,
                            'title' => substr($userMessage, 0, 50) . '...'
                        ]);
                        $conversationId = $newConv->id;
                    }

                    // Guardamos el registro de la consulta
                    $query = AiQuery::create([
                        'user_id'         => Auth::id(),
                        'course_id'       => $request->course_id,
                        'conversation_id' => $conversationId, // Importante vincularlos
                        'question'        => $userMessage,
                        'response'        => $botReply,
                    ]);

                    return response()->json([
                        'success' => true,
                        'data' => [
                            'response' => $botReply,
                            'conversation_id' => $conversationId,
                            'query_id' => $query->id,
                            'timestamp' => now()
                        ]
                    ]);
                });
            }

            return response()->json([
                'success' => false,
                'error' => 'Error de Gemini: ' . ($response->json('error.message') ?? 'Desconocido')
            ], 500);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    /** Obtener el historial de una conversación específica */
    public function getHistory(int $conversationId): JsonResponse
    {
        // Buscamos la conversación y sus mensajes vinculados (AiQuery)
        $history = AiQuery::where('conversation_id', $conversationId)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['success' => true, 'data' => $history]);
    }

    /** Listar todas las conversaciones del usuario */
    public function getConversations(): JsonResponse
    {
        $conversations = ChatbotConversation::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'title'      => $c->title ?? 'Conversación nueva',
                'created_at' => $c->created_at->diffForHumans(),
                'course_id'  => $c->course_id,
            ]);

        return response()->json(['success' => true, 'data' => $conversations]);
    }

    /** Eliminar una conversación y sus mensajes */
    public function deleteConversation(int $id): JsonResponse
    {
        DB::transaction(function () use ($id) {
            AiQuery::where('conversation_id', $id)->where('user_id', Auth::id())->delete();
            ChatbotConversation::where('id', $id)->where('user_id', Auth::id())->delete();
        });

        return response()->json(['success' => true]);
    }

    /** Calificar si la respuesta fue útil */
    public function rateResponse(Request $request, int $queryId): JsonResponse
    {
        $request->validate(['helpful' => 'required|boolean']);
        
        AiQuery::where('id', $queryId)
            ->where('user_id', Auth::id())
            ->update(['was_helpful' => $request->helpful]);
            
        return response()->json(['success' => true]);
    }
}