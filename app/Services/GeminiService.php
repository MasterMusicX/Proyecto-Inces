<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * Service for interacting with Google Gemini Pro API
 */
class GeminiService
{
    protected Client $client;
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', '');
        $this->model  = config('services.gemini.model', 'gemini-pro');
        $this->client = new Client(['timeout' => 60]);
    }

    /**
     * Send messages to Gemini and get a response
     */
    public function chat(array $messages, string $systemPrompt = ''): string
    {
        if (empty($this->apiKey)) {
            return '⚠️ La API de Gemini no está configurada. Por favor, agregue GEMINI_API_KEY en el archivo .env';
        }

        try {
            $contents = [];

            if (!empty($systemPrompt)) {
                $contents[] = ['role' => 'user',  'parts' => [['text' => $systemPrompt]]];
                $contents[] = ['role' => 'model', 'parts' => [['text' => 'Entendido. Procedo según las instrucciones.']]];
            }

            foreach ($messages as $message) {
                $role       = $message['role'] === 'assistant' ? 'model' : 'user';
                $contents[] = ['role' => $role, 'parts' => [['text' => $message['content']]]];
            }

            $url      = $this->baseUrl . $this->model . ':generateContent?key=' . $this->apiKey;
            $response = $this->client->post($url, [
                'json' => [
                    'contents'         => $contents,
                    'generationConfig' => [
                        'temperature'     => (float) config('services.gemini.temperature', 0.7),
                        'maxOutputTokens' => (int)   config('services.gemini.max_tokens', 2048),
                        'topP'            => 0.8,
                        'topK'            => 40,
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ],
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'No se pudo generar una respuesta. Intente de nuevo.';

        } catch (GuzzleException $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return 'Error al conectar con el servicio de IA. Verifique la configuración de la API y su conexión a internet.';
        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            return 'Error inesperado en el servicio de IA. Por favor, intente nuevamente.';
        }
    }

    /** Simple single-prompt generation */
    public function generate(string $prompt): string
    {
        return $this->chat([['role' => 'user', 'content' => $prompt]]);
    }

    /** Analyze document content and return structured data */
    public function analyzeDocument(string $text, string $title): array
    {
        $truncated = mb_substr($text, 0, 8000);
        $prompt    = "Analiza el siguiente documento educativo del INCES titulado: \"{$title}\"\n\n"
            . "CONTENIDO:\n{$truncated}\n\n"
            . "Responde SOLO con JSON válido, sin texto adicional:\n"
            . '{"summary":"resumen en 3 párrafos","keywords":["k1","k2"],"topics":["t1","t2"],"language":"es","difficulty_level":"básico"}';

        $response = $this->generate($prompt);

        try {
            preg_match('/\{.*\}/s', $response, $matches);
            if (!empty($matches[0])) {
                $result = json_decode($matches[0], true);
                if (json_last_error() === JSON_ERROR_NONE) return $result;
            }
        } catch (\Exception $e) {
            Log::error('Document analysis JSON parse error: ' . $e->getMessage());
        }

        return [
            'summary'          => "Documento analizado: {$title}",
            'keywords'         => [],
            'topics'           => [],
            'language'         => 'es',
            'difficulty_level' => 'intermedio',
        ];
    }

    /** Generate chatbot response with full educational context */
    public function chatWithContext(
        string $userMessage,
        array  $history,
        string $courseContext    = '',
        string $documentContext = ''
    ): string {
        $systemPrompt = $this->buildSystemPrompt($courseContext, $documentContext);
        $messages     = array_merge($history, [['role' => 'user', 'content' => $userMessage]]);
        return $this->chat($messages, $systemPrompt);
    }

    /** Summarize content for students */
    public function summarize(string $content, int $maxWords = 150): string
    {
        return $this->generate(
            "Resume en máximo {$maxWords} palabras, en español venezolano, para estudiantes del INCES:\n\n{$content}"
        );
    }

    private function buildSystemPrompt(string $courseCtx = '', string $docCtx = ''): string
    {
        $p = "Eres el Asistente Virtual INCES, tutor educativo especializado del Instituto Nacional de Capacitación y Educación Socialista de Venezuela.\n\n"
           . "Tu misión:\n"
           . "- Ayudar a los estudiantes a comprender los contenidos educativos\n"
           . "- Responder preguntas sobre cursos y materiales de formación\n"
           . "- Explicar conceptos técnicos de manera clara y sencilla\n"
           . "- Responder SIEMPRE en español, de manera profesional y amigable\n"
           . "- Si no sabes algo, sé honesto y sugiere buscar en los materiales del curso\n\n";

        if ($courseCtx)  $p .= "CONTEXTO DEL CURSO:\n{$courseCtx}\n\n";
        if ($docCtx)     $p .= "DOCUMENTOS DISPONIBLES:\n{$docCtx}\n\n";

        return $p;
    }
}
