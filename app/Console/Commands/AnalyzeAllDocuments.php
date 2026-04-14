<?php
namespace App\Console\Commands;

use App\Jobs\ProcessDocumentJob;
use App\Models\Resource;
use Illuminate\Console\Command;

class AnalyzeAllDocuments extends Command
{
    protected $signature   = 'lms:analyze-documents {--force : Re-analyze already processed documents}';
    protected $description = 'Queue AI analysis for all unprocessed document resources';

    public function handle(): int
    {
        $query = Resource::whereIn('type', ['pdf', 'docx', 'xlsx', 'pptx'])
            ->where('is_published', true);

        if (!$this->option('force')) {
            $query->whereDoesntHave('analysis', fn($q) => $q->where('status', 'completed'));
        }

        $resources = $query->get();
        $this->info("Queuing {$resources->count()} documents for AI analysis...");

        foreach ($resources as $resource) {
            ProcessDocumentJob::dispatch($resource);
            $this->line("  → Queued: {$resource->title}");
        }

        $this->info('Done! Run `php artisan queue:work` to process them.');
        return Command::SUCCESS;
    }
}
