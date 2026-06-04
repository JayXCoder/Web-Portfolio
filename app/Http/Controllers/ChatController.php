<?php

namespace App\Http\Controllers;

use App\Services\ChatContextService;
use App\Services\OllamaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(
        private OllamaService $ollamaService,
        private ChatContextService $chatContext
    ) {}

    public function index(): View
    {
        $isAvailable = $this->ollamaService->isAvailable();
        $models = $this->ollamaService->getModels();

        return view('pages.chat', compact('isAvailable', 'models'));
    }

    public function status(): JsonResponse
    {
        return response()->json([
            'available' => $this->ollamaService->isAvailable(),
            'api_url' => $this->ollamaService->getApiUrl(),
            'model' => $this->ollamaService->getModel(),
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'context' => 'sometimes|array',
        ]);

        if (! $this->ollamaService->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'The assistant is offline. Ollama is not reachable.',
                'error' => 'ollama_unavailable',
            ], 503);
        }

        $message = $request->input('message');
        $history = $request->input('context', []);

        $system = config('chat.system_prompt')
            ."\n\n## Portfolio knowledge\n"
            .$this->chatContext->buildKnowledgeBase();

        $messages = [
            ['role' => 'system', 'content' => $system],
        ];

        foreach ($history as $turn) {
            if (! is_array($turn) || empty($turn['role']) || empty($turn['content'])) {
                continue;
            }
            $role = $turn['role'] === 'assistant' ? 'assistant' : 'user';
            $messages[] = ['role' => $role, 'content' => (string) $turn['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = $this->ollamaService->chat($messages, [
            'temperature' => 0.35,
        ]);

        return response()->json([
            'success' => $response['success'],
            'message' => $response['response'] ?? config('chat.refusal_hint'),
            'context' => array_merge($history, [
                ['role' => 'user', 'content' => $message],
                ['role' => 'assistant', 'content' => $response['response'] ?? ''],
            ]),
            'timestamp' => now()->format('H:i'),
            'error' => $response['error'] ?? null,
        ]);
    }

    public function debug(): JsonResponse
    {
        $apiUrl = config('ollama.api_url');
        $model = config('ollama.model');

        $connectivityTest = $this->testConnectivity($apiUrl);
        $apiTest = $this->testApiEndpoint($apiUrl);
        $modelTest = $this->testModelAvailability($apiUrl, $model);

        return response()->json([
            'configuration' => [
                'api_url' => $apiUrl,
                'model' => $model,
                'timeout' => config('ollama.timeout'),
            ],
            'tests' => [
                'connectivity' => $connectivityTest,
                'api_endpoint' => $apiTest,
                'model_availability' => $modelTest,
            ],
            'recommendations' => $this->getRecommendations($connectivityTest, $apiTest, $modelTest),
        ]);
    }

    private function testConnectivity(string $apiUrl): array
    {
        try {
            $response = Http::timeout(5)->get($apiUrl);

            return [
                'status' => 'success',
                'message' => 'Connection successful',
                'status_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'status_code' => null,
            ];
        }
    }

    private function testApiEndpoint(string $apiUrl): array
    {
        try {
            $response = Http::timeout(5)->get($apiUrl.'/api/tags');

            return [
                'status' => $response->successful() ? 'success' : 'error',
                'message' => $response->successful() ? 'API endpoint accessible' : 'API endpoint error',
                'status_code' => $response->status(),
                'response' => $response->successful() ? $response->json() : $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'status_code' => null,
            ];
        }
    }

    private function testModelAvailability(string $apiUrl, string $model): array
    {
        try {
            $response = Http::timeout(10)->get($apiUrl.'/api/tags');
            if ($response->successful()) {
                $data = $response->json();
                $models = $data['models'] ?? [];
                $modelExists = collect($models)->contains('name', $model);

                return [
                    'status' => $modelExists ? 'success' : 'warning',
                    'message' => $modelExists ? 'Model is available' : 'Model not found',
                    'available_models' => array_column($models, 'name'),
                    'requested_model' => $model,
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Could not fetch model list',
                'status_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function getRecommendations(array $connectivity, array $api, array $model): array
    {
        $recommendations = [];

        if ($connectivity['status'] === 'error') {
            $recommendations[] = 'Check if Ollama service is running on '.config('ollama.api_url');
            $recommendations[] = 'Verify network connectivity to the Ollama server';
            $recommendations[] = 'Check firewall settings';
        }

        if ($api['status'] === 'error') {
            $recommendations[] = 'Verify Ollama API is accessible at /api/tags endpoint';
            $recommendations[] = 'Check if Ollama service is properly started';
        }

        if ($model['status'] === 'warning') {
            $recommendations[] = 'Model '.config('ollama.model').' not found. Available models: '.implode(', ', $model['available_models'] ?? []);
            $recommendations[] = 'Update OLLAMA_MODEL in .env file to use an available model';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'All tests passed! Ollama service should be working correctly.';
        }

        return $recommendations;
    }
}
