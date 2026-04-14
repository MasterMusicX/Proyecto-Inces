<?php
namespace App\Jobs;

use App\Models\Resource;
use App\Services\DocumentProcessorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(public Resource $resource) {}

    public function handle(DocumentProcessorService $processor): void
    {
        $processor->processResource($this->resource);
    }
}
