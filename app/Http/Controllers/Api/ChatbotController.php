<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiQuery;
use App\Models\ChatbotConversation;
use App\Models\KnowledgeBase;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Enviar mensaje con lógica estricta de Base de Conocimiento e Identidad del INCES
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message'         => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer',
            'course_id'       => 'nullable|integer',
        ]);

        try {
            $userMessage = trim($request->message);
            $apiKey = env('GROQ_API_KEY');
            $model = env('GROQ_MODEL', 'llama3-8b-8192');

            // 1. Obtener entradas de la Base de Conocimiento del INCES
            $kbEntries = KnowledgeBase::all();
            
            $kbContext = "";
            foreach ($kbEntries as $entry) {
                $kbContext .= "- PREGUNTA/TEMA: {$entry->question}\n  RESPUESTA OFICIAL: {$entry->answer}\n  CATEGORÍA: {$entry->category}\n\n";
            }

            // 2. Obtener lista de Cursos activos en el INCES para contexto académico
            $courses = Course::where('is_published', true)->get(['id', 'title', 'description']);
            $coursesContext = "";
            foreach ($courses as $c) {
                $coursesContext .= "- Curso: {$c->title}. Descripción: {$c->description}\n";
            }

            // 3. Coincidencia directa en Base de Datos
            $directMatch = KnowledgeBase::where(function($q) use ($userMessage) {
                $q->where('question', 'LIKE', "%{$userMessage}%")
                  ->orWhere('answer', 'LIKE', "%{$userMessage}%");
            })->first();

            // 4. Prompt de Sistema Estricto para el Asistente/Instructor MTP del INCES
            $systemPrompt = <<<PROMPT
Eres el Asistente Virtual e Instructor MTP (Maestro Técnico Productivo) del INCES (Instituto Nacional de Capacitación y Educación Socialista).

NORMAS Y DIRECTRICES OBLIGATORIAS:
1. Tu rol es ÚNICAMENTE actuar como Instructor MTP del INCES y brindar información académica sobre el INCES, sus cursos, metodologías, inscripciones, evaluaciones, certificaciones y los temas registrados en la Base de Conocimiento oficial.
2. Si el usuario hace una pregunta cuya respuesta esté presente en la Base de Conocimiento o en la oferta de cursos expuesta abajo, respóndela de forma amable, clara y didáctica.
3. Si el usuario te pregunta por un tema o información que NO esté disponible en la Base de Conocimiento o que NO esté directamente relacionada con el INCES, tu respuesta DEBE SER OBLIGATORIAMENTE la siguiente frase (o una ligera variación muy cercana):
"No dispongo de esa información en este momento. Próximamente buscaré información más adelante sobre ese tema. Recuerda que como Instructor del INCES solo manejo información referente al INCES y a nuestros programas de formación."
4. Si el usuario realiza preguntas sobre temas ajenos al INCES (deportes, farándula, política externa, tareas escolares externas, chistes, etc.), debes responder que como Instructor del INCES solo manejas información referente al INCES.
5. NUNCA inventes información fuera del ámbito del INCES.

BASE DE CONOCIMIENTO OFICIAL DEL INCES:
{$kbContext}

OFERTA ACADÉMICA Y CURSOS DEL INCES:
{$coursesContext}
PROMPT;

            $botReply = null;

            // Intentar procesamiento con IA (Groq API) si la API key existe
            if (!empty($apiKey)) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'temperature' => 0.2, // Baja temperatura para mantener fidelidad estricta
                    'max_tokens' => 600,
                ]);

                if ($response->successful()) {
                    $botReply = trim($response->json('choices.0.message.content'));
                }
            }

            // Fallback si la API de IA no responde o no está configurada
            if (empty($botReply)) {
                if ($directMatch) {
                    $botReply = "Saludos. Como Instructor MTP del INCES te informo: " . $directMatch->answer;
                } else {
                    $botReply = "No dispongo de esa información en este momento. Próximamente buscaré información más adelante sobre ese tema. Recuerda que como Instructor del INCES solo manejo información referente al INCES y a nuestros programas de formación.";
                }
            }

            // Incrementar contador de vistas de la entrada si hubo coincidencia directa
            if ($directMatch) {
                try {
                    $directMatch->increment('views');
                } catch (\Exception $e) {}
            }

            // 5. Persistencia en Base de Datos
            return DB::transaction(function () use ($request, $userMessage, $botReply) {
                $conversationId = $request->conversation_id;
                
                if (!$conversationId) {
                    $newConv = ChatbotConversation::create([
                        'user_id'   => Auth::id(),
                        'course_id' => $request->course_id,
                        'title'     => Str::limit($userMessage, 35)
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
            Log::error('Excepción en ChatbotController: ' . $e->getMessage());
            
            $fallbackMessage = "No dispongo de esa información en este momento. Próximamente buscaré información más adelante sobre ese tema. Recuerda que como Instructor del INCES solo manejo información referente al INCES.";
            
            return response()->json([
                'success' => true,
                'data' => [
                    'response' => $fallbackMessage,
                ]
            ]);
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
