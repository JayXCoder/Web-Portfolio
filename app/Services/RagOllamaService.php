<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RagOllamaService
{
    public function apiUrl(): string
    {
        return (string) config('ollama.api_url');
    }

    public function chatModel(): string
    {
        return (string) config('ai.chat_model');
    }

    public function embeddingModel(): string
    {
        return (string) config('ai.embedding_model');
    }

    /** @return array{success: bool, content?: string, thinking?: string|null, raw?: array<string, mixed>, error?: string} */
    public function chat(array $messages, array $options = []): array
    {
        try {
            $payload = [
                'model' => $this->chatModel(),
                'messages' => $messages,
                'stream' => false,
                'think' => $options['think'] ?? config('ai.think', true),
                'options' => [
                    'temperature' => $options['temperature'] ?? 0.25,
                    'top_p' => $options['top_p'] ?? 0.9,
                    'num_predict' => $options['num_predict'] ?? (int) config('ai.answer_max_tokens', 768),
                ],
            ];

            if (isset($options['format'])) {
                $payload['format'] = $options['format'];
            }

            $response = Http::timeout((int) config('ollama.timeout', 300))
                ->post($this->apiUrl().'/api/chat', $payload);

            if (! $response->successful()) {
                return ['success' => false, 'error' => 'Chat request failed: '.$response->status()];
            }

            $data = $response->json();

            return [
                'success' => true,
                'content' => (string) data_get($data, 'message.content', ''),
                'thinking' => data_get($data, 'message.thinking'),
                'raw' => $data,
            ];
        } catch (\Throwable $e) {
            Log::warning('RAG Ollama chat failed', ['error' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * @param  string|list<string>  $input
     * @return array{success: bool, embeddings?: list<list<float>>, raw?: array<string, mixed>, error?: string}
     */
    public function embed(string|array $input): array
    {
        try {
            $response = Http::timeout((int) config('ollama.timeout', 300))
                ->post($this->apiUrl().'/api/embed', [
                    'model' => $this->embeddingModel(),
                    'input' => $input,
                    'truncate' => false,
                ]);

            if (! $response->successful()) {
                return ['success' => false, 'error' => 'Embedding request failed: '.$response->status()];
            }

            $data = $response->json();
            if (! is_array($data['embeddings'] ?? null) || $data['embeddings'] === []) {
                return ['success' => false, 'error' => 'Ollama returned no embeddings.'];
            }

            return ['success' => true, 'embeddings' => $data['embeddings'], 'raw' => $data];
        } catch (\Throwable $e) {
            Log::warning('RAG Ollama embedding failed', ['error' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /** @return array{available: bool, chat_model: bool, embedding_model: bool, models: list<string>} */
    public function health(): array
    {
        try {
            $response = Http::timeout(5)->get($this->apiUrl().'/api/tags');
            $models = $response->successful()
                ? collect($response->json('models', []))->pluck('name')->filter()->values()->all()
                : [];

            return [
                'available' => $response->successful(),
                'chat_model' => in_array($this->chatModel(), $models, true),
                'embedding_model' => in_array($this->embeddingModel(), $models, true),
                'models' => $models,
            ];
        } catch (\Throwable) {
            return ['available' => false, 'chat_model' => false, 'embedding_model' => false, 'models' => []];
        }
    }
}
