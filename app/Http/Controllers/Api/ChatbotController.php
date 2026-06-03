<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiQuery;
use App\Models\ChatbotConversation;
use App\Models\KnowledgeBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Enviar un mensaje al chatbot (Groq Cloud con Base de Conocimientos e Historial)
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
            $apiKey = env('GROQ_API_KEY');
            $model = env('GROQ_MODEL', 'llama3-8b-8192');

            if (!$apiKey) {
                return response()->json(['success' => false, 'error' => 'Configuración incompleta: Falta API Key de Groq.'], 500);
            }

            // 1. Extraemos el contexto del INCES (Los manuales que subió el Admin)
            $knowledge = KnowledgeBase::where('status', 'active')
                ->pluck('content')
                ->implode("\n\n");

            // 2. Combinamos tus reglas estrictas con la base de conocimientos
            $systemPrompt = "Eres un Maestro Técnico Productivo (MTP) virtual del INCES Construcción. Tu objetivo es ayudar a los estudiantes exclusivamente con dudas sobre informática, programación, seguridad laboral y administración. Debes ser didáctico, respetuoso y profesional.\n\n" .
                            "REGLA ESTRICTA: Si el usuario te pregunta sobre CUALQUIER otro tema (recetas de cocina, chistes, deportes, política, etc.), DEBES responder EXACTAMENTE con esta frase y no agregar nada más: 'Lo siento, mi función como MTP del INCES es estrictamente académica. Solo puedo ayudarte con temas de nuestros cursos.'\n\n" .
                            "BASA TUS RESPUESTAS EN ESTA INFORMACIÓN INSTITUCIONAL:\n" . $knowledge;

            // 3. Llamada a la API ultrarrápida de Groq
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.1, // Súper estricto para que no alucine
                'max_tokens' => 800,
            ]);

            if ($response->successful()) {
                // Navegamos por la respuesta JSON que nos manda Groq
                $botReply = $response->json('choices.0.message.content');

                if (!$botReply) {
                    return response()->json(['success' => false, 'error' => 'Respuesta vacía de la IA.'], 500);
                }

                // 4. Lógica de Persistencia (DB Transaction)
                return DB::transaction(function () use ($request, $userMessage, $botReply) {
                    
                    $conversationId = $request->conversation_id;
                    
                    // Si no hay ID, creamos una conversación nueva
                    if (!$conversationId) {
                        $newConv = ChatbotConversation::create([
                            'user_id' => Auth::id(),
                            'course_id' => $request->course_id,
                            'title' => substr($userMessage, 0, 50) . '...'
                        ]);
                        $conversationId = $newConv->id;
                    } else {
                        // Actualizamos el updated_at para que suba en la lista
                        ChatbotConversation::where('id', $conversationId)->touch();
                    }

                    // Guardamos el registro de la consulta
                    $query = AiQuery::create([
                        'user_id'         => Auth::id(),
                        'course_id'       => $request->course_id,
                        'conversation_id' => $conversationId,
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

            Log::error('Error de Groq API: ' . $response->body());
            return response()->json([
                'success' => false,
                'error' => 'Error de conexión con el motor de IA.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Excepción en Chatbot: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error interno procesando tu solicitud.'], 500);
        }
    }

    /** Obtener el historial de una conversación específica */
    public function getHistory(int $conversationId): JsonResponse
    {
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