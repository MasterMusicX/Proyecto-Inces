<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDocumentJob;
use App\Models\DocumentAnalysis;
use App\Models\Resource;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function __construct(protected GeminiService $geminiService) {}

    /** Get analysis for a resource */
    public function analysis(Resource $resource): JsonResponse
    {
        // Check access
        $isEnrolled = $resource->course->students()->where('users.id', Auth::id())->exists()
            || Auth::user()->isAdmin()
            || $resource->course->instructor_id === Auth::id();

        abort_unless($isEnrolled, 403);

        $analysis = $resource->analysis;

        if (!$analysis || $analysis->status === 'failed') {
            ProcessDocumentJob::dispatch($resource);
            return response()->json([
                'success' => true,
                'status'  => 'queued',
                'message' => 'El análisis ha sido enviado a la cola de procesamiento.',
            ]);
        }

        return response()->json([
            'success'  => true,
            'status'   => $analysis->status,
            'data'     => $analysis,
        ]);
    }

    /** Ask a question about a specific document */
    public function askAboutDocument(Request $request, Resource $resource): JsonResponse
    {
        $request->validate(['question' => 'required|string|max:1000']);

        $isEnrolled = $resource->course->students()->where('users.id', Auth::id())->exists()
            || Auth::user()->isAdmin()
            || $resource->course->instructor_id === Auth::id();

        abort_unless($isEnrolled, 403);

        $analysis = $resource->analysis;
        $context  = '';

        if ($analysis && $analysis->isCompleted()) {
            $context = "Documento: {$resource->title}\n\n"
                . "Resumen: {$analysis->summary}\n\n"
                . "Contenido: " . substr($analysis->extracted_text ?? '', 0, 5000);
        }

        $prompt = $context
            ? "Basándote en el siguiente documento educativo del INCES, responde la pregunta:\n\n{$context}\n\nPregunta: {$request->question}"
            : "Responde esta pregunta sobre el INCES: {$request->question}";

        $response = $this->geminiService->generate($prompt);

        return response()->json([
            'success'  => true,
            'response' => $response,
        ]);
    }

    /** Summarize a document */
    public function summarize(Resource $resource): JsonResponse
    {
        $isEnrolled = $resource->course->students()->where('users.id', Auth::id())->exists()
            || Auth::user()->isAdmin()
            || $resource->course->instructor_id === Auth::id();

        abort_unless($isEnrolled, 403);

        $analysis = $resource->analysis;
        if (!$analysis || !$analysis->isCompleted()) {
            return response()->json(['success' => false, 'message' => 'El documento aún no ha sido analizado.'], 404);
        }

        $summary = $this->geminiService->summarize($analysis->extracted_text ?? $analysis->summary);

        return response()->json(['success' => true, 'summary' => $summary]);
    }
}
