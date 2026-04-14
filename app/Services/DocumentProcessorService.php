<?php
namespace App\Services;

use App\Models\DocumentAnalysis;
use App\Models\Resource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Service for extracting text from documents and generating AI analysis
 */
class DocumentProcessorService
{
    public function __construct(protected GeminiService $geminiService) {}

    /** Process a resource: extract text + AI analysis */
    public function processResource(Resource $resource): DocumentAnalysis
    {
        $analysis = DocumentAnalysis::updateOrCreate(
            ['resource_id' => $resource->id],
            ['status' => 'processing', 'error_message' => null]
        );

        try {
            $text = $this->extractText($resource);

            if (empty(trim($text))) {
                return $analysis->tap(fn($a) => $a->update([
                    'status'        => 'failed',
                    'error_message' => 'No se pudo extraer texto del documento.',
                ]));
            }

            $aiResult = $this->geminiService->analyzeDocument($text, $resource->title);

            $analysis->update([
                'extracted_text' => $text,
                'summary'        => $aiResult['summary']          ?? null,
                'keywords'       => $aiResult['keywords']         ?? [],
                'topics'         => $aiResult['topics']           ?? [],
                'language'       => $aiResult['language']         ?? 'es',
                'status'         => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error("DocumentProcessor failed [resource_id={$resource->id}]: " . $e->getMessage());
            $analysis->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        }

        return $analysis->fresh();
    }

    /** Dispatch extraction based on file type */
    public function extractText(Resource $resource): string
    {
        if (!$resource->file_path) return '';

        $path = Storage::disk('public')->path($resource->file_path);
        if (!file_exists($path)) {
            $path = storage_path('app/public/' . $resource->file_path);
        }
        if (!file_exists($path)) return '';

        return match($resource->type) {
            'pdf'   => $this->extractPdf($path),
            'docx'  => $this->extractDocx($path),
            'xlsx'  => $this->extractXlsx($path),
            'pptx'  => $this->extractPptx($path),
            default => '',
        };
    }

    protected function extractPdf(string $path): string
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            return $parser->parseFile($path)->getText();
        } catch (\Exception $e) {
            Log::warning("PDF extract failed: " . $e->getMessage());
            return '';
        }
    }

    protected function extractDocx(string $path): string
    {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);
            $text    = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } elseif (method_exists($element, 'getElements')) {
                        foreach ($element->getElements() as $child) {
                            if (method_exists($child, 'getText')) {
                                $text .= $child->getText() . ' ';
                            }
                        }
                        $text .= "\n";
                    }
                }
            }
            return $text;
        } catch (\Exception $e) {
            Log::warning("DOCX extract failed: " . $e->getMessage());
            return '';
        }
    }

    protected function extractXlsx(string $path): string
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $text        = '';
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                $text .= "=== Hoja: " . $sheet->getTitle() . " ===\n";
                foreach ($sheet->getRowIterator() as $row) {
                    $cells = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $val = $cell->getValue();
                        if ($val !== null && $val !== '') $cells[] = (string)$val;
                    }
                    if (!empty($cells)) $text .= implode(' | ', $cells) . "\n";
                }
            }
            return $text;
        } catch (\Exception $e) {
            Log::warning("XLSX extract failed: " . $e->getMessage());
            return '';
        }
    }

    protected function extractPptx(string $path): string
    {
        try {
            $zip  = new \ZipArchive();
            $text = '';
            if ($zip->open($path) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $name = $zip->getNameIndex($i);
                    if (str_starts_with($name, 'ppt/slides/slide') && str_ends_with($name, '.xml')) {
                        $xml  = $zip->getFromIndex($i);
                        $text .= strip_tags($xml) . "\n";
                    }
                }
                $zip->close();
            }
            return $text;
        } catch (\Exception $e) {
            Log::warning("PPTX extract failed: " . $e->getMessage());
            return '';
        }
    }
}
