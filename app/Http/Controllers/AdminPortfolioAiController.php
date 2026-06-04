<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\OllamaService;
use App\Services\PortfolioAiService;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPortfolioAiController extends Controller
{
    public function __construct(
        private PortfolioAiService $portfolioAi,
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
        $request->validate([
            'markdown_files' => 'nullable|array',
            'markdown_files.*' => 'file|mimes:md,txt|max:5120',
            'markdown_paste' => 'nullable|string|max:100000',
        ]);

        $parts = [];

        if ($request->hasFile('markdown_files')) {
            foreach ($request->file('markdown_files') as $file) {
                $parts[] = '# '.$file->getClientOriginalName()."\n\n".$file->get();
            }
        }

        if ($request->filled('markdown_paste')) {
            $parts[] = $request->input('markdown_paste');
        }

        if (empty($parts)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one markdown file or paste content.',
            ], 422);
        }

        $combined = implode("\n\n---\n\n", $parts);
        $result = $this->portfolioAi->generateFromMarkdown($combined);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function saveDraft(Request $request): JsonResponse
    {
        $request->validate([
            'portfolio' => 'required|array',
            'portfolio.title' => 'required|string|max:255',
            'portfolio.short_description' => 'required|string|max:500',
            'portfolio.description' => 'required|string',
            'portfolio.technologies' => 'required|array|min:1',
            'portfolio.category' => 'required|string|max:100',
            'portfolio.features' => 'required|array|min:1',
        ]);

        $normalized = $this->portfolioAi->normalizePortfolio($request->input('portfolio'));

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
