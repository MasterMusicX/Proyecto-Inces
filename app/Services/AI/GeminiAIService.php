<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AiQuery;
use App\Models\User;

/**
 * Servicio principal de integración con Google Gemini AI
 * Maneja todas las consultas de IA del sistema LMS
 */
class GeminiAIService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    private int $maxTokens;
    private float $temperature;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-1.5-pro');
        $this->maxTokens = config('services.gemini.max_tokens', 8192);
        $this->temperature = config('services.gemini.temperature', 0.7);
    }

    /**
     * Genera una respuesta del chatbot educativo
     */
    public function chat(
        string $userMessage,
        array $conversationHistory = [],
        ?string $systemContext = null,
        ?int $courseId = null
    ): array {
        $startTime = microtime(true);

        $systemPrompt = $this->buildSystemPrompt($systemContext);
        $contents = $this->buildConversationContents($conversationHistory, $userMessage);

        try {
            $response = $this->callGeminiAPI($contents, $systemPrompt);
            $responseTime = (int)((microtime(true) - $startTime) * 1000);

            return [
                'success' => true,
                'message' => $response['text'],
                'tokens_used' => $response['tokens_used'] ?? null,
                'response_time_ms' => $responseTime,
            ];
        } catch (\Exception $e) {
            Log::error('GeminiAI chat error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lo siento, no pude procesar tu consulta en este momento. Por favor intenta de nuevo.',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Analiza un documento y genera resumen, keywords y análisis
     */
    public function analyzeDocument(string $documentText, string $documentTitle = ''): array
    {
        $prompt = $this->buildDocumentAnalysisPrompt($documentText, $documentTitle);

        try {
            $response = $this->callGeminiAPI([[
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ]]);

            $analysisText = $response['text'];
            return $this->parseDocumentAnalysis($analysisText);
        } catch (\Exception $e) {
            Log::error('GeminiAI document analysis error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Genera un resumen de contenido educativo
     */
    public function summarizeContent(string $content, string $context = ''): string
    {
        $prompt = "Eres un experto en educación técnica y vocacional del INCES Venezuela.
        
Genera un resumen conciso y claro del siguiente contenido educativo.
El resumen debe:
- Ser de máximo 3 párrafos
- Destacar los puntos más importantes
- Usar lenguaje accesible para estudiantes
- Estar en español

" . ($context ? "Contexto: {$context}\n\n" : '') . "Contenido a resumir:\n{$content}";

        try {
            $response = $this->callGeminiAPI([[
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ]]);
            return $response['text'];
        } catch (\Exception $e) {
            Log::error('GeminiAI summarize error: ' . $e->getMessage());
            return 'No se pudo generar el resumen automáticamente.';
        }
    }

    /**
     * Responde una pregunta sobre el contenido de un documento
     */
    public function answerFromDocument(string $question, string $documentContent, string $documentTitle = ''): array
    {
        $prompt = "Eres un asistente educativo especializado del INCES (Instituto Nacional de Capacitación y Educación Socialista de Venezuela).

Tienes acceso al siguiente documento: \"{$documentTitle}\"

CONTENIDO DEL DOCUMENTO:
{$documentContent}

---

Responde la siguiente pregunta del estudiante basándote ÚNICAMENTE en el contenido del documento anterior.
Si la información no está en el documento, indícalo claramente.
Responde en español de forma clara y pedagógica.

PREGUNTA DEL ESTUDIANTE: {$question}";

        try {
            $response = $this->callGeminiAPI([[
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ]]);

            return [
                'success' => true,
                'answer' => $response['text'],
                'tokens_used' => $response['tokens_used'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('GeminiAI answerFromDocument error: ' . $e->getMessage());
            return [
                'success' => false,
                'answer' => 'No se pudo procesar tu pregunta. Intenta de nuevo.',
            ];
        }
    }

    /**
     * Búsqueda semántica en contenido
     */
    public function semanticSearch(string $query, array $documents): array
    {
        $docsText = '';
        foreach ($documents as $idx => $doc) {
            $docsText .= "\n[DOC_{$idx}] Título: {$doc['title']}\nContenido: " . substr($doc['content'], 0, 500) . "\n";
        }

        $prompt = "Analiza la siguiente consulta de búsqueda y determina cuáles de los documentos listados son más relevantes.
        
Consulta: \"{$query}\"

Documentos disponibles:
{$docsText}

Responde SOLO con un JSON array de índices ordenados por relevancia (del más al menos relevante).
Formato: [0, 2, 1] (solo los índices de documentos relevantes)
Si ninguno es relevante, responde: []";

        try {
            $response = $this->callGeminiAPI([[
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ]]);

            $indices = json_decode(trim($response['text']), true) ?? [];
            return array_filter($indices, fn($i) => isset($documents[$i]));
        } catch (\Exception $e) {
            Log::error('GeminiAI semantic search error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Construye el prompt del sistema para el chatbot educativo
     */
    private function buildSystemPrompt(?string $context = null): string
    {
        $basePrompt = "Eres INCA (Inteligencia Nacional de Capacitación Asistida), el asistente virtual educativo oficial del INCES (Instituto Nacional de Capacitación y Educación Socialista de Venezuela).

Tu misión es:
- Ayudar a los estudiantes a aprender y comprender los contenidos educativos
- Responder preguntas sobre los cursos y materiales didácticos
- Explicar conceptos técnicos de manera clara y accesible
- Motivar y guiar a los estudiantes en su proceso de formación
- Orientar sobre el uso de la plataforma LMS

Características de tus respuestas:
- Siempre en español venezolano
- Tono amigable, motivador y pedagógico
- Respuestas claras, concisas y bien estructuradas
- Cuando sea necesario, usa ejemplos prácticos
- Si no tienes la información, indica cómo puede el estudiante obtenerla
- Nunca inventes información sobre cursos o contenidos específicos

El INCES es una institución venezolana dedicada a la formación técnica y vocacional de trabajadores y jóvenes venezolanos.";

        if ($context) {
            $basePrompt .= "\n\nCONTEXTO ADICIONAL:\n{$context}";
        }

        return $basePrompt;
    }

    /**
     * Construye el historial de conversación para la API
     */
    private function buildConversationContents(array $history, string $newMessage): array
    {
        $contents = [];

        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg['role'],
                'parts' => [['text' => $msg['content']]]
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $newMessage]]
        ];

        return $contents;
    }

    /**
     * Construye el prompt para análisis de documentos
     */
    private function buildDocumentAnalysisPrompt(string $text, string $title): string
    {
        return "Analiza el siguiente documento educativo del INCES y proporciona un análisis completo.

TÍTULO DEL DOCUMENTO: {$title}

CONTENIDO:
" . substr($text, 0, 15000) . "

---

Proporciona el análisis en el siguiente formato JSON exacto:
{
  \"summary\": \"Resumen ejecutivo del documento en 2-3 párrafos\",
  \"keywords\": [\"palabra1\", \"palabra2\", \"palabra3\", \"...\"],
  \"topics\": [\"tema principal 1\", \"tema principal 2\", \"...\"],
  \"key_concepts\": [\"concepto1\", \"concepto2\", \"...\"],
  \"ai_analysis\": \"Análisis detallado del contenido, su relevancia educativa y aplicaciones prácticas\"
}

Responde ÚNICAMENTE con el JSON, sin texto adicional, sin bloques de código markdown.";
    }

    /**
     * Parsea la respuesta de análisis de documentos
     */
    private function parseDocumentAnalysis(string $rawText): array
    {
        // Limpiar posibles markdown code blocks
        $clean = preg_replace('/```json\s*|\s*```/', '', $rawText);
        $clean = trim($clean);

        $data = json_decode($clean, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Fallback: extraer información básica
            return [
                'summary' => substr($rawText, 0, 500),
                'keywords' => [],
                'topics' => [],
                'key_concepts' => [],
                'ai_analysis' => $rawText,
            ];
        }

        return [
            'summary' => $data['summary'] ?? '',
            'keywords' => $data['keywords'] ?? [],
            'topics' => $data['topics'] ?? [],
            'key_concepts' => $data['key_concepts'] ?? [],
            'ai_analysis' => $data['ai_analysis'] ?? '',
        ];
    }

    /**
     * Realiza la llamada a la API de Gemini
     */
    private function callGeminiAPI(array $contents, ?string $systemInstruction = null): array
    {
        $url = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";

        $body = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $this->temperature,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => $this->maxTokens,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ],
        ];

        if ($systemInstruction) {
            $body['systemInstruction'] = [
                'parts' => [['text' => $systemInstruction]]
            ];
        }

        $response = Http::timeout(60)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $body);

        if (!$response->successful()) {
            throw new \Exception("Gemini API error: " . $response->status() . " - " . $response->body());
        }

        $data = $response->json();

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $tokensUsed = ($data['usageMetadata']['promptTokenCount'] ?? 0)
                    + ($data['usageMetadata']['candidatesTokenCount'] ?? 0);

        if (empty($text)) {
            throw new \Exception('Gemini API returned empty response');
        }

        return [
            'text' => $text,
            'tokens_used' => $tokensUsed,
        ];
    }
}
