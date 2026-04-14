<?php
namespace App\Http\Controllers\Student;
    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\AiQuery;
    use App\Models\ChatbotConversation;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Http; 

class ChatbotController extends Controller
{
    /** Send a message to the chatbot */
    public function sendMessage(Request $request): JsonResponse
    {
        // Validamos los datos que llegan del chat
        $request->validate([
            'message'         => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer',
            'course_id'       => 'nullable|integer',
        ]);

        try {
            $userMessage = $request->message;
            $apiKey = env('GEMINI_API_KEY');

            // 1. Verificamos que la clave exista en el .env
            if (!$apiKey) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Falta la API Key de Gemini en el archivo .env'
                ], 500);
            }

            // 2. Hacemos la llamada a Google saltando la verificación SSL local (withoutVerifying)
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $userMessage]
                        ]
                    ]
                ]
            ]);

            // 3. Si Google responde OK (Código 200)
            if ($response->successful()) {
                $botReply = $response->json('candidates.0.content.parts.0.text');

                // Verificamos si la respuesta vino vacía por alguna razón de Google
                if (!$botReply) {
                    return response()->json([
                        'success' => false, 
                        'error' => 'Google respondió, pero el mensaje vino vacío.'
                    ], 500);
                }

                // Guardamos en la base de datos
                AiQuery::create([
                    'user_id'   => Auth::id(),
                    'course_id' => $request->course_id,
                    'question'  => $userMessage,
                    'response'  => $botReply,
                ]);

                return response()->json([
                    'success' => true, 
                    'data' => [
                        'response' => $botReply,
                        'conversation_id' => $request->conversation_id ?? uniqid(),
                        'timestamp' => now()
                    ]
                ]);
            } 
            
            // 4. Si Google rechaza la petición, sacamos el chisme exacto de por qué
            $googleError = $response->json('error.message') ?? 'Error desconocido de la API';
            
            return response()->json([
                'success' => false, 
                'error' => 'Google dice: ' . $googleError
            ], 500);

        } catch (\Exception $e) {
            // 5. Si explota Laravel (Base de datos, variables, etc), te muestra el error real
            return response()->json([
                'success' => false, 
                'error' => 'Error interno de Laravel: ' . $e->getMessage()
            ], 500);
        }
    }

    /** Get messages in a conversation */
    public function getHistory(int $conversationId): JsonResponse
    {
        $conversation = ChatbotConversation::where('id', $conversationId)
            ->where('user_id', Auth::id())
            ->with('messages')
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $conversation]);
    }

    /** List all user conversations */
    public function getConversations(): JsonResponse
    {
        $conversations = ChatbotConversation::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get()
            ->map(function ($c) {
                return [
                    'id'         => $c->id,
                    'title'      => $c->title ?? 'Conversación',
                    'created_at' => $c->created_at->diffForHumans(),
                    'messages'   => $c->messages()->count(),
                ];
            });

        return response()->json(['success' => true, 'data' => $conversations]);
    }

    /** Delete a conversation */
    public function deleteConversation(int $id): JsonResponse
    {
        ChatbotConversation::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
    }

    /** Rate a chatbot response */
    public function rateResponse(Request $request, int $queryId): JsonResponse
    {
        $request->validate(['helpful' => 'required|boolean']);
        AiQuery::where('id', $queryId)->where('user_id', Auth::id())
            ->update(['was_helpful' => $request->helpful]);
        return response()->json(['success' => true]);
    }
}