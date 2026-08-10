<?php

namespace Tests\Unit;

use App\Services\ChatContextService;
use App\Services\KnowledgeRetrievalService;
use App\Services\RagChatService;
use App\Services\RagOllamaService;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class RagChatSourceHintsTest extends TestCase
{
    public function test_award_questions_also_search_linkedin_posts(): void
    {
        $service = new RagChatService(
            $this->createMock(RagOllamaService::class),
            $this->createMock(KnowledgeRetrievalService::class),
            $this->createMock(ChatContextService::class),
        );

        $method = new ReflectionMethod(RagChatService::class, 'sourceHints');
        $method->setAccessible(true);

        $hints = $method->invoke($service, 'Did jay win any award in UPEX?');

        $this->assertContains('achievement', $hints);
        $this->assertContains('linkedin_post', $hints);
    }
}
