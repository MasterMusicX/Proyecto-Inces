<?php
namespace App\Services;

use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\Course;
use App\Models\DocumentAnalysis;
use App\Models\KnowledgeBase;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Service for handling chatbot conversations
 */
class ChatbotService
{
    public function __construct(protected GeminiService $geminiService) {}

    /** Process incoming user message and return AI response */
    public function processMessage(
        User   $user,
        string $message,
        ?int   $conversationId = null,
        ?int   $courseId       = null
    ): array {
        $conversation = $this->getOrCreateConversation($user, $conversationId, $courseId);

        // Save user message
        ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'user_id'         => $user->id,
            'role'            => 'user',
            'content'         => $message,
        ]);

        // Build context from course and documents
        $courseContext    = $this->buildCourseContext($courseId);
        $documentContext = $this->buildDocumentContext($courseId);

        // Get last 10 messages as history
        $history = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get()
            ->map(fn($m) => ['role' => $m->role === 'assistant' ? 'assistant' : 'user', 'content' => $m->content])
            ->toArray();

        // Remove the last message (just saved) so we don't duplicate
        if (!empty($history)) array_pop($history);

        // Try knowledge base first (fast path)
        $kbAnswer = $this->searchKnowledgeBase($message);

        if ($kbAnswer) {
            $aiResponse = $kbAnswer . "\n\n---\n*📚 Fuente: Base de Conocimiento INCES*";
        } else {
            $aiResponse = $this->geminiService->chatWithContext(
                $message, $history, $courseContext, $documentContext
            );
        }

        // Save assistant response
        $assistantMsg = ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'user_id'         => $user->id,
            'role'            => 'assistant',
            'content'         => $aiResponse,
            'metadata'        => ['from_kb' => (bool) $kbAnswer],
        ]);

        // Auto-title conversation after first exchange
        if ($conversation->messages()->count() <= 2 && !$conversation->title) {
            $conversation->update(['title' => Str::limit($message, 60)]);
        }

        return [
            'conversation_id' => $conversation->id,
            'message_id'      => $assistantMsg->id,
            'response'        => $aiResponse,
            'timestamp'       => $assistantMsg->created_at->toISOString(),
        ];
    }

    protected function getOrCreateConversation(User $user, ?int $conversationId, ?int $courseId): ChatbotConversation
    {
        if ($conversationId) {
            $conv = ChatbotConversation::where('id', $conversationId)
                ->where('user_id', $user->id)
                ->first();
            if ($conv) return $conv;
        }
        return ChatbotConversation::create([
            'user_id'    => $user->id,
            'session_id' => (string) Str::uuid(),
            'course_id'  => $courseId,
            'is_active'  => true,
        ]);
    }

    protected function buildCourseContext(?int $courseId): string
    {
        if (!$courseId) return '';
        $course = Course::with(['modules', 'instructor'])->find($courseId);
        if (!$course) return '';

        $ctx  = "CURSO: {$course->title}\n";
        $ctx .= "Descripción: {$course->description}\n";
        $ctx .= "Instructor: {$course->instructor->name}\n";
        $ctx .= "Nivel: {$course->level_label}\n";
        $ctx .= "Módulos: " . $course->modules->pluck('title')->implode(', ') . "\n";
        return $ctx;
    }

    protected function buildDocumentContext(?int $courseId): string
    {
        $query = DocumentAnalysis::where('status', 'completed')->with('resource');
        if ($courseId) {
            $query->whereHas('resource', fn($q) => $q->where('course_id', $courseId));
        }
        $analyses = $query->limit(4)->get();
        if ($analyses->isEmpty()) return '';

        $ctx = "DOCUMENTOS DEL CURSO:\n";
        foreach ($analyses as $a) {
            $ctx .= "• {$a->resource->title}: " . Str::limit($a->summary, 200) . "\n";
        }
        return $ctx;
    }

    protected function searchKnowledgeBase(string $query): ?string
    {
        $queryLower = mb_strtolower($query);
        $entries    = KnowledgeBase::where('is_active', true)->get();

        foreach ($entries as $entry) {
            $questionLower = mb_strtolower($entry->question);
            similar_text($queryLower, $questionLower, $pct);
            if ($pct > 65) {
                $entry->increment('views');
                return $entry->answer;
            }
        }
        return null;
    }
}
