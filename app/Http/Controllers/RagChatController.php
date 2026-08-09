<?php

namespace App\Http\Controllers;

use App\Services\OllamaService;
use App\Services\RagChatService;
use App\Services\RagOllamaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RagChatController extends Controller
{
    public function __construct(
        private RagChatService $rag,
        private RagOllamaService $ollama,
        private ChatController $legacy,
        private OllamaService $legacyOllama,
    ) {}

    public function index(): View
    {
        $health = $this->ollama->health();
        $isAvailable = config('rag.enabled')
            ? $health['available'] && $health['chat_model'] && $health['embedding_model']
            : $this->legacyOllama->isAvailable();
        $models = $health['models'];

        return view('pages.chat', compact('isAvailable', 'models'));
    }

    public function status(): JsonResponse
    {
        $health = $this->ollama->health();

        return response()->json([
            'available' => $health['available'] && $health['chat_model'] && $health['embedding_model'],
            'rag_enabled' => (bool) config('rag.enabled'),
            'chat_model' => $this->ollama->chatModel(),
            'embedding_model' => $this->ollama->embeddingModel(),
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $validated = $this->validateRequest($request);
        if (! config('rag.enabled')) {
            return $this->legacy->sendMessage($request);
        }

        try {
            $result = $this->rag->respond($validated['message'], $validated['context'] ?? []);

            return response()->json($result, $result['success'] ? 200 : 502);
        } catch (\Throwable) {
            return response()->json([
                'success' => false,
                'message' => 'The assistant is temporarily unavailable. Please try again.',
                'error' => 'rag_unavailable',
            ], 503);
        }
    }

    public function stream(Request $request): StreamedResponse
    {
        $validated = $this->validateRequest($request);

        return response()->stream(function () use ($validated, $request) {
            $emit = function (string $event, array $data): void {
                echo json_encode(['event' => $event, 'data' => $data], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            };

            try {
                if (! config('rag.enabled')) {
                    $emit('status', ['stage' => 'answering']);
                    $legacy = $this->legacy->sendMessage($request)->getData(true);
                    $emit('complete', $legacy);

                    return;
                }

                $result = $this->rag->respond(
                    $validated['message'],
                    $validated['context'] ?? [],
                    fn (string $stage) => $emit('status', ['stage' => $stage]),
                );
                $emit($result['success'] ? 'complete' : 'error', $result);
            } catch (\Throwable) {
                $emit('error', [
                    'success' => false,
                    'message' => 'The assistant is temporarily unavailable. Please try again.',
                    'error' => 'rag_unavailable',
                ]);
            }
        }, 200, [
            'Content-Type' => 'application/x-ndjson',
            'Cache-Control' => 'no-cache, no-store',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /** @return array{message: string, context?: list<array{role: string, content: string}>} */
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'message' => 'required|string|max:2000',
            'context' => 'sometimes|array|max:8',
            'context.*.role' => 'required_with:context|in:user,assistant',
            'context.*.content' => 'required_with:context|string|max:4000',
        ]);
    }
}
