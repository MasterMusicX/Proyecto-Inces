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
     * Enviar mensaje con lógica híbrida (Base de Datos + IA)
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

            // 1. BUSCAR EN BASE DE DATOS (Prioridad)
            $knowledgeEntry = KnowledgeBase::where('status', 'active')
                ->where(function($q) use ($userMessage) {
                    $q->where('question', 'LIKE', '%' . $userMessage . '%')
                      ->orWhere('tags', 'LIKE', '%' . $userMessage . '%');
                })
                ->first();

            // 2. DEFINIR CONTEXTO PARA LA IA
            if ($knowledgeEntry) {
                // Si hay info en BD, usamos esa respuesta como base estricta
                $systemPrompt = "Eres un MTP del INCES. Tu objetivo es explicar esta información al estudiante de forma didáctica y amable: " . $knowledgeEntry->answer;
                $isFallback = false;
            } else {
                // Si NO hay info, usamos el conocimiento general de la IA restringido al INCES
                $systemPrompt = "Eres un MTP del INCES. El estudiante pregunta sobre: '$userMessage'. " .
                                "Si el tema no tiene relación con el INCES, informática, seguridad laboral o administración, responde: 'Lo siento, mi función como MTP del INCES es estrictamente académica. Solo puedo ayudarte con temas de nuestros cursos.'";
                $isFallback = true;
            }

            // 3. LLAMADA A LA API DE GROQ
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => $isFallback ? 0.3 : 0.1,
                'max_tokens' => 600,
            ]);

            if (!$response->successful()) {
                Log::error('Error de Groq API: ' . $response->body());
                return response()->json(['success' => false, 'error' => 'Error en el servicio de IA.'], 500);
            }

            $botReply = $response->json('choices.0.message.content');

            // 4. PERSISTENCIA EN BASE DE DATOS
            return DB::transaction(function () use ($request, $userMessage, $botReply) {
                $conversationId = $request->conversation_id;
                
                if (!$conversationId) {
                    $newConv = ChatbotConversation::create([
                        'user_id'   => Auth::id(),
                        'course_id' => $request->course_id,
                        'title'     => substr($userMessage, 0, 40) . '...'
                    ]);
                    $conversationId = $newConv->id;
                } else {
                    ChatbotConversation::where('id', $conversationId)->touch();
                }

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
                        'response'        => $botReply,
                        'conversation_id' => $conversationId,
                        'query_id'        => $query->id
                    ]
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Excepción en Chatbot: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error interno.'], 500);
        }
    }

    /** Obtener el historial */
    public function getHistory(int $conversationId): JsonResponse
    {
        $history = AiQuery::where('conversation_id', $conversationId)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json(['success' => true, 'data' => $history]);
    }

    /** Listar conversaciones */
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

    /** Eliminar conversación */
    public function deleteConversation(int $id): JsonResponse
    {
        DB::transaction(function () use ($id) {
            AiQuery::where('conversation_id', $id)->where('user_id', Auth::id())->delete();
            ChatbotConversation::where('id', $id)->where('user_id', Auth::id())->delete();
        });
        return response()->json(['success' => true]);
    }

    /** Calificar respuesta */
    public function rateResponse(Request $request, int $queryId): JsonResponse
    {
        $request->validate(['helpful' => 'required|boolean']);
        AiQuery::where('id', $queryId)->where('user_id', Auth::id())->update(['was_helpful' => $request->helpful]);
        return response()->json(['success' => true]);
    }
}
