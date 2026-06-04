<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\OllamaService;
use App\Services\PortfolioAiJobStore;
use App\Services\PortfolioAiService;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdminPortfolioAiController extends Controller
{
    public function __construct(
        private PortfolioAiService $portfolioAi,
        private PortfolioAiJobStore $jobStore,
        private PortfolioService $portfolioService,
        private OllamaService $ollama
    ) {}

    public function status(): JsonResponse
    {
        return response()->json([
            'available' => $this->ollama->isAvailable(),
            'api_url' => $this->ollama->getApiUrl(),
            'model' => $this->ollama->getModel(),
        ]);
    }

    public function generate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'markdown_files' => 'nullable|array',
                'markdown_files.*' => [
                    'file',
                    'max:5120',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        if (! $value instanceof UploadedFile) {
                            return;
                        }

                        $ext = strtolower($value->getClientOriginalExtension());
                        if (! in_array($ext, ['md', 'txt'], true)) {
                            $fail('Each file must be a .md or .txt file.');
                        }
                    },
                ],
                'markdown_paste' => 'nullable|string|max:100000',
            ]);

            $parts = [];

            if ($request->hasFile('markdown_files')) {
                foreach ($request->file('markdown_files') as $file) {
                    if (! $file instanceof UploadedFile) {
                        continue;
                    }

                    $parts[] = '# '.$file->getClientOriginalName()."\n\n".$this->readMarkdownFile($file);
                }
            }

            if ($request->filled('markdown_paste')) {
                $parts[] = $request->input('markdown_paste');
            }

            if ($parts === []) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provide at least one markdown file or paste content.',
                ], 422);
            }

            $combined = implode("\n\n---\n\n", $parts);
            $jobId = $this->jobStore->create($combined);

            app()->terminating(function () use ($jobId): void {
                set_time_limit((int) config('portfolio-ai.generation_time_limit', 600));

                try {
                    $markdown = $this->jobStore->getMarkdown($jobId);

                    if ($markdown === null) {
                        return;
                    }

                    $result = $this->portfolioAi->generateFromMarkdown($markdown);
                    $this->jobStore->finish($jobId, $result);
                } catch (Throwable $e) {
                    Log::error('Portfolio AI background generation failed', [
                        'job_id' => $jobId,
                        'message' => $e->getMessage(),
                        'exception' => $e::class,
                    ]);

                    $this->jobStore->finish($jobId, [
                        'success' => false,
                        'message' => 'Generation failed on the server. Check storage/logs/laravel.log.',
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'job_id' => $jobId,
                'status' => 'processing',
                'message' => 'Generation started. This usually takes 1–3 minutes — keep this tab open.',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Portfolio AI generate failed', [
                'message' => $e->getMessage(),
                'exception' => $e::class,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error while generating portfolio. Check storage/logs/laravel.log on the server.',
            ], 500);
        }
    }

    public function jobStatus(string $jobId): JsonResponse
    {
        if (! Str::isUuid($jobId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid job id.',
            ], 404);
        }

        $status = $this->jobStore->publicStatus($jobId);

        if ($status === null) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or expired. Start generation again.',
            ], 404);
        }

        return response()->json($status);
    }

    private function readMarkdownFile(UploadedFile $file): string
    {
        $path = $file->getRealPath();

        if (! is_string($path) || $path === '' || ! is_readable($path)) {
            throw new \RuntimeException('Could not read uploaded file: '.$file->getClientOriginalName());
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new \RuntimeException('Could not read uploaded file: '.$file->getClientOriginalName());
        }

        return $contents;
    }

    public function saveDraft(Request $request): JsonResponse
    {
        try {
            return $this->saveDraftResponse($request);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Portfolio AI save failed', [
                'message' => $e->getMessage(),
                'exception' => $e::class,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error while saving portfolio.',
            ], 500);
        }
    }

    private function saveDraftResponse(Request $request): JsonResponse
    {
        $raw = $request->input('portfolio');

        if (! is_array($raw)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid portfolio payload.',
            ], 422);
        }

        $normalized = $this->portfolioAi->normalizePortfolio($raw);

        Validator::make(
            ['portfolio' => $normalized],
            [
                'portfolio' => 'required|array',
                'portfolio.title' => 'required|string|max:255',
                'portfolio.short_description' => 'required|string|max:500',
                'portfolio.description' => 'required|string',
                'portfolio.technologies' => 'required|array|min:1',
                'portfolio.category' => 'required|string|max:100',
                'portfolio.features' => 'required|array|min:1',
            ]
        )->validate();

        $slug = $normalized['slug'];
        if (Portfolio::where('slug', $slug)->exists()) {
            $normalized['slug'] = $slug.'-'.Str::random(4);
        }

        $payload = $normalized;
        if (! empty($normalized['image_urls'])) {
            $payload['image_urls'] = implode(',', $normalized['image_urls']);
        }
        unset($payload['image_urls']);

        $portfolio = $this->portfolioService->createPortfolio($payload);

        return response()->json([
            'success' => true,
            'message' => 'Portfolio created successfully.',
            'redirect' => route('admin.portfolios.edit', $portfolio),
            'portfolio_id' => $portfolio->id,
        ]);
    }
}
