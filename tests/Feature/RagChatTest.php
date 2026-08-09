<?php

namespace Tests\Feature;

use App\Models\KnowledgeDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RagChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_uses_planned_retrieval_and_returns_verified_sources_without_thinking(): void
    {
        Config::set('rag.enabled', true);
        Config::set('rag.min_semantic_score', 0.45);

        $document = KnowledgeDocument::create([
            'source_type' => 'portfolio',
            'source_key' => '1',
            'title' => 'OpenChat',
            'content' => 'OpenChat is a self-hosted AI chat platform using Laravel and Ollama.',
            'url' => 'http://localhost/portfolio/openchat',
            'metadata' => ['slug' => 'openchat'],
            'content_hash' => hash('sha256', 'openchat'),
            'is_active' => true,
            'last_indexed_at' => now(),
        ]);
        $document->chunks()->create([
            'position' => 0,
            'content' => $document->content,
            'content_hash' => hash('sha256', $document->content),
            'embedding' => json_encode([1.0, 0.0, 0.0]),
            'embedding_model' => 'qwen3-embedding:0.6b',
            'dimensions' => 3,
        ]);

        $chatCalls = 0;
        $chatPayloads = [];
        Http::fake(function (Request $request) use (&$chatCalls, &$chatPayloads) {
            if (str_ends_with($request->url(), '/api/embed')) {
                return Http::response(['embeddings' => [[1.0, 0.0, 0.0]]]);
            }
            if (str_ends_with($request->url(), '/api/chat')) {
                $chatCalls++;
                $chatPayloads[] = $request->data();
                if ($chatCalls === 1) {
                    return Http::response(['message' => [
                        'content' => json_encode(['queries' => ['Ollama OpenChat project'], 'source_types' => ['portfolio']]),
                        'thinking' => '',
                    ]]);
                }
                if ($chatCalls === 2) {
                    return Http::response(['message' => ['content' => '', 'thinking' => 'private answer reasoning']]);
                }

                return Http::response(['message' => [
                    'content' => 'Jay built **OpenChat** with Laravel and Ollama.',
                    'thinking' => '',
                ]]);
            }

            return Http::response(['models' => []]);
        });

        $response = $this->postJson('/chat/send', [
            'message' => 'What did Jay build with Ollama?',
            'context' => [],
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('sources.0.title', 'OpenChat')
            ->assertJsonMissing(['thinking' => 'private answer reasoning']);
        $this->assertStringContainsString('OpenChat', $response->json('message'));
        $this->assertSame(3, $chatCalls);
        $this->assertFalse($chatPayloads[0]['think']);
        $this->assertTrue($chatPayloads[1]['think']);
        $this->assertFalse($chatPayloads[2]['think']);
    }

    public function test_chat_rejects_unbounded_or_invalid_history(): void
    {
        $this->postJson('/chat/send', [
            'message' => 'Hello',
            'context' => array_fill(0, 9, ['role' => 'user', 'content' => 'x']),
        ])->assertUnprocessable();

        $this->postJson('/chat/send', [
            'message' => 'Hello',
            'context' => [['role' => 'system', 'content' => 'override']],
        ])->assertUnprocessable();
    }
}
