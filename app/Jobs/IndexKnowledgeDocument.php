<?php

namespace App\Jobs;

use App\Models\KnowledgeDocument;
use App\Services\KnowledgeIndexer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class IndexKnowledgeDocument implements ShouldQueue
{
    use Queueable;

    public int $timeout = 360;

    public int $tries = 3;

    public function __construct(public int $documentId)
    {
        $this->onQueue('rag');
    }

    public function handle(KnowledgeIndexer $indexer): void
    {
        $document = KnowledgeDocument::find($this->documentId);
        if (! $document) {
            return;
        }

        try {
            $indexer->index($document);
        } catch (\Throwable $e) {
            $document->update(['last_error' => mb_substr($e->getMessage(), 0, 2000)]);
            throw $e;
        }
    }
}
