<?php

namespace App\Http\Controllers;

use App\Services\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    protected $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    /**
     * Display the chatbot page
     */
    public function index(): View
    {
        $isAvailable = $this->ollamaService->isAvailable();
        $models = $this->ollamaService->getModels();
        
        return view('pages.chat', compact('isAvailable', 'models'));
    }

    /**
     * Send message to Ollama API
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'context' => 'sometimes|array'
        ]);

        $message = $request->input('message');
        $context = $request->input('context', []);

        $response = $this->ollamaService->generateResponse($message, $context);

        return response()->json([
            'success' => $response['success'],
            'message' => $response['response'],
            'context' => $response['context'] ?? [],
            'timestamp' => now()->format('H:i'),
            'error' => $response['error'] ?? null
        ]);
    }

    /**
     * Debug Ollama connection
     */
    public function debug(): JsonResponse
    {
        $apiUrl = config('ollama.api_url');
        $model = config('ollama.model');
        
        // Test basic connectivity
        $connectivityTest = $this->testConnectivity($apiUrl);
        
        // Test API endpoint
        $apiTest = $this->testApiEndpoint($apiUrl);
        
        // Test model availability
        $modelTest = $this->testModelAvailability($apiUrl, $model);
        
        return response()->json([
            'configuration' => [
                'api_url' => $apiUrl,
                'model' => $model,
                'timeout' => config('ollama.timeout')
            ],
            'tests' => [
                'connectivity' => $connectivityTest,
                'api_endpoint' => $apiTest,
                'model_availability' => $modelTest
            ],
            'recommendations' => $this->getRecommendations($connectivityTest, $apiTest, $modelTest)
        ]);
    }
    
    private function testConnectivity(string $apiUrl): array
    {
        try {
            $response = Http::timeout(5)->get($apiUrl);
            return [
                'status' => 'success',
                'message' => 'Connection successful',
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'status_code' => null
            ];
        }
    }
    
    private function testApiEndpoint(string $apiUrl): array
    {
        try {
            $response = Http::timeout(5)->get($apiUrl . '/api/tags');
            return [
                'status' => $response->successful() ? 'success' : 'error',
                'message' => $response->successful() ? 'API endpoint accessible' : 'API endpoint error',
                'status_code' => $response->status(),
                'response' => $response->successful() ? $response->json() : $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'status_code' => null
            ];
        }
    }
    
    private function testModelAvailability(string $apiUrl, string $model): array
    {
        try {
            $response = Http::timeout(10)->get($apiUrl . '/api/tags');
            if ($response->successful()) {
                $data = $response->json();
                $models = $data['models'] ?? [];
                $modelExists = collect($models)->contains('name', $model);
                
                return [
                    'status' => $modelExists ? 'success' : 'warning',
                    'message' => $modelExists ? 'Model is available' : 'Model not found',
                    'available_models' => array_column($models, 'name'),
                    'requested_model' => $model
                ];
            }
            return [
                'status' => 'error',
                'message' => 'Could not fetch model list',
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    private function getRecommendations(array $connectivity, array $api, array $model): array
    {
        $recommendations = [];
        
        if ($connectivity['status'] === 'error') {
            $recommendations[] = 'Check if Ollama service is running on ' . config('ollama.api_url');
            $recommendations[] = 'Verify network connectivity to the Ollama server';
            $recommendations[] = 'Check firewall settings';
        }
        
        if ($api['status'] === 'error') {
            $recommendations[] = 'Verify Ollama API is accessible at /api/tags endpoint';
            $recommendations[] = 'Check if Ollama service is properly started';
        }
        
        if ($model['status'] === 'warning') {
            $recommendations[] = 'Model ' . config('ollama.model') . ' not found. Available models: ' . implode(', ', $model['available_models'] ?? []);
            $recommendations[] = 'Update OLLAMA_MODEL in .env file to use an available model';
        }
        
        if (empty($recommendations)) {
            $recommendations[] = 'All tests passed! Ollama service should be working correctly.';
        }
        
        return $recommendations;
    }
}
