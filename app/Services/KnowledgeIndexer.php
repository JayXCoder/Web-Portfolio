<?php

namespace App\Services;

use App\Models\KnowledgeDocument;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class KnowledgeIndexer
{
    public function __construct(
        private RagOllamaService $ollama,
        private KnowledgeChunker $chunker,
    ) {}

    public function index(KnowledgeDocument $document): int
    {
        if (! $document->is_active) {
            $document->chunks()->delete();

            return 0;
        }

        $chunks = $this->chunker->chunk($document->content);
        if ($chunks === []) {
            $document->update(['last_error' => 'Document contained no indexable text.']);

            return 0;
        }

        $vectors = [];
        foreach (array_chunk($chunks, max(1, (int) config('rag.embedding_batch_size', 16))) as $batch) {
            $result = $this->ollama->embed($batch);
            if (! $result['success']) {
                throw new RuntimeException($result['error'] ?? 'Embedding failed.');
            }
            array_push($vectors, ...$result['embeddings']);
        }

        if (count($vectors) !== count($chunks)) {
            throw new RuntimeException('Embedding count did not match chunk count.');
        }

        DB::transaction(function () use ($document, $chunks, $vectors) {
            $document->chunks()->delete();
            foreach ($chunks as $position => $content) {
                $vector = array_map('floatval', $vectors[$position]);
                $document->chunks()->create([
                    'position' => $position,
                    'content' => $content,
                    'content_hash' => hash('sha256', $content),
                    'embedding' => json_encode($vector, JSON_THROW_ON_ERROR),
                    'embedding_model' => $this->ollama->embeddingModel(),
                    'dimensions' => count($vector),
                ]);
            }
            $document->update(['last_indexed_at' => now(), 'last_error' => null]);
        });

        return count($chunks);
    }
}
