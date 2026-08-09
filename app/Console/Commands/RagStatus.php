<?php

namespace App\Console\Commands;

use App\Models\KnowledgeChunk;
use App\Models\KnowledgeDocument;
use App\Models\KnowledgeSyncRun;
use App\Services\RagOllamaService;
use Illuminate\Console\Command;

class RagStatus extends Command
{
    protected $signature = 'rag:status';

    protected $description = 'Show RAG index and model health';

    public function handle(RagOllamaService $ollama): int
    {
        $health = $ollama->health();
        $lastSync = KnowledgeSyncRun::latest('id')->first();
        $this->table(['Signal', 'Value'], [
            ['RAG enabled', config('rag.enabled') ? 'yes' : 'no'],
            ['Ollama reachable', $health['available'] ? 'yes' : 'no'],
            ['Chat model', $ollama->chatModel().' '.($health['chat_model'] ? '✓' : 'missing')],
            ['Embedding model', $ollama->embeddingModel().' '.($health['embedding_model'] ? '✓' : 'missing')],
            ['Active documents', KnowledgeDocument::where('is_active', true)->count()],
            ['Chunks', KnowledgeChunk::count()],
            ['Documents with errors', KnowledgeDocument::whereNotNull('last_error')->count()],
            ['Last sync', $lastSync?->finished_at?->toIso8601String() ?? 'never'],
        ]);

        return ($health['available'] && $health['chat_model'] && $health['embedding_model'])
            ? self::SUCCESS
            : self::FAILURE;
    }
}
