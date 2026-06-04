<?php

namespace App\Http\Controllers;

use App\Services\ChatContextService;
use App\Services\OllamaService;
use App\Support\ChatMessageFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
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

        $relatedPortfolios = $this->chatContext->findRelatedPortfolios($message);
        $matchedSkills = $this->chatContext->findMatchingSkills($message);

        $system = config('chat.system_prompt')
            ."\n\n## Portfolio knowledge\n"
            .$this->chatContext->buildKnowledgeBase();

        $turnHint = $this->chatContext->buildTurnHint($message);
        if ($turnHint !== '') {
            $system .= "\n\n".$turnHint;
        }

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

        $rawReply = $response['response'] ?? config('chat.refusal_hint');

        if ($response['success'] && $matchedSkills !== [] && $this->replyDeniesListedSkills($rawReply, $matchedSkills)) {
            $rawReply = $this->buildSkillAffirmation($matchedSkills, $relatedPortfolios);
        }

        if ($response['success']) {
            $slugsFromReply = ChatMessageFormatter::extractProjectSlugs($rawReply);
            $relatedPortfolios = $relatedPortfolios
                ->merge($this->chatContext->portfoliosBySlugs($slugsFromReply))
                ->unique('id')
                ->values();

            $relatedPortfolios = $this->chatContext->refineRelatedPortfolios($relatedPortfolios, $rawReply, $message);
        }

        $reply = $response['success']
            ? ChatMessageFormatter::sanitizeReply($rawReply)
            : $rawReply;

        return response()->json([
            'success' => $response['success'],
            'message' => $reply,
            'message_html' => $response['success'] ? ChatMessageFormatter::toHtml($reply) : null,
            'related_projects' => $this->chatContext->formatProjectsForChat($relatedPortfolios),
            'matched_skills' => $matchedSkills,
            'context' => array_merge($history, [
                ['role' => 'user', 'content' => $message],
                ['role' => 'assistant', 'content' => $reply],
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

    /**
     * @param  list<string>  $skills
     */
    private function replyDeniesListedSkills(string $reply, array $skills): bool
    {
        $lower = strtolower($reply);

        $denyPhrases = [
            'do not see',
            "don't see",
            'does not have',
            "doesn't have",
            'not listed',
            'no specific experience',
            'cannot confirm',
            "can't confirm",
            'not mentioned',
        ];

        foreach ($denyPhrases as $phrase) {
            if (str_contains($lower, $phrase)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<string>  $skills
     */
    private function buildSkillAffirmation(array $skills, Collection $relatedPortfolios): string
    {
        $list = implode(', ', $skills);
        $text = "Yes. Jay has experience with {$list}, listed on his Skills page under AI/ML and related areas.";

        if ($relatedPortfolios->isNotEmpty()) {
            $names = $relatedPortfolios->pluck('title')->map(fn ($t) => "**{$t}**")->implode(', ');
            $text .= " Related portfolio work includes {$names}.";
        } else {
            $text .= ' Ask about a specific project or his AI/ML stack for more detail.';
        }

        return $text;
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
