<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $apiUrl;

    protected string $model;

    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('ollama.api_url');
        $this->model = config('ollama.model');
        $this->timeout = config('ollama.timeout');
    }

    public function generateResponse(string $message, array $context = []): array
    {
        return $this->chat(
            [
                ['role' => 'user', 'content' => $message],
            ],
            $context
        );
    }

    /**
     * Chat completion (preferred for structured output).
     */
    public function chat(array $messages, array $options = []): array
    {
        try {
            $payload = array_merge([
                'model' => $this->model,
                'messages' => $messages,
                'stream' => false,
                'options' => [
                    'temperature' => $options['temperature'] ?? 0.4,
                    'top_p' => $options['top_p'] ?? 0.9,
                ],
            ], array_filter([
                'format' => $options['format'] ?? null,
            ]));

            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl.'/api/chat', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['message']['content'] ?? '';

                return [
                    'success' => true,
                    'response' => $content,
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => 'API request failed: '.$response->status(),
                'response' => 'Sorry, I encountered an error. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('Ollama API Error: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response' => 'Sorry, I\'m having trouble connecting right now. Please try again later.',
            ];
        }
    }

    /**
     * Legacy generate endpoint fallback.
     */
    public function generate(string $prompt, array $options = []): array
    {
        try {
            $payload = [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => $options['temperature'] ?? 0.4,
                ],
            ];

            if (! empty($options['format'])) {
                $payload['format'] = $options['format'];
            }

            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl.'/api/generate', $payload);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'response' => $data['response'] ?? '',
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => 'API request failed: '.$response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Ollama generate error: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get($this->apiUrl.'/api/tags');

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Ollama service unavailable', [
                'url' => $this->apiUrl,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getModels(): array
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl.'/api/tags');

            if ($response->successful()) {
                return $response->json()['models'] ?? [];
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Ollama Models Error: '.$e->getMessage());

            return [];
        }
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
