<?php

namespace Tests\Unit;

use App\Services\KnowledgeChunker;
use App\Services\KnowledgeRetrievalService;
use Tests\TestCase;

class KnowledgeChunkerTest extends TestCase
{
    public function test_chunking_is_deterministic_and_preserves_content_overlap(): void
    {
        $chunker = new KnowledgeChunker;
        $content = str_repeat('Laravel Ollama retrieval knowledge. ', 80);

        $first = $chunker->chunk($content);
        $second = $chunker->chunk($content);

        $this->assertSame($first, $second);
        $this->assertGreaterThan(1, count($first));
        $this->assertSame(count($first), count(array_unique($first)));
    }

    public function test_cosine_similarity_orders_related_vectors(): void
    {
        $service = $this->getMockBuilder(KnowledgeRetrievalService::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $this->assertEqualsWithDelta(1.0, $service->cosine([1, 0, 0], [1, 0, 0]), 0.0001);
        $this->assertEqualsWithDelta(0.0, $service->cosine([1, 0, 0], [0, 1, 0]), 0.0001);
        $this->assertSame(0.0, $service->cosine([1, 0], [1, 0, 0]));
    }
}
