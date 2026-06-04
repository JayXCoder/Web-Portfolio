<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PortfolioAiService
{
    public function __construct(
        private OllamaService $ollama
    ) {}

    /**
     * Generate one portfolio draft from combined markdown sources.
     */
    public function generateFromMarkdown(string $markdown): array
    {
        if (! $this->ollama->isAvailable()) {
            return [
                'success' => false,
                'message' => 'Ollama is not reachable at '.config('ollama.api_url').'. Check OLLAMA_HOST and that the model is pulled.',
            ];
        }

        $system = config('portfolio-ai.system_prompt');
        $userContent = "Convert the following project markdown into one portfolio JSON object:\n\n---\n".$markdown."\n---";

        $result = $this->ollama->chat([
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $userContent],
        ], [
            'format' => 'json',
            'temperature' => 0.35,
        ]);

        if (! $result['success']) {
            return [
                'success' => false,
                'message' => $result['error'] ?? 'Ollama request failed.',
            ];
        }

        $parsed = $this->parsePortfolioJson($result['response']);

        if (! $parsed['success']) {
            Log::warning('Portfolio AI JSON parse failed, retrying with generate endpoint', [
                'raw' => substr($result['response'], 0, 500),
            ]);

            $fallback = $this->ollama->generate(
                $system."\n\n".$userContent."\n\nRespond with JSON only.",
                ['format' => 'json', 'temperature' => 0.35]
            );

            if (! $fallback['success']) {
                return [
                    'success' => false,
                    'message' => 'Could not parse AI response as portfolio JSON.',
                ];
            }

            $parsed = $this->parsePortfolioJson($fallback['response']);
        }

        if (! $parsed['success']) {
            return $parsed;
        }

        return [
            'success' => true,
            'portfolio' => $this->normalizePortfolio($parsed['data']),
            'message' => 'Portfolio draft generated. Review before saving.',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function normalizePortfolio(array $data): array
    {
        $categories = config('portfolio-ai.categories', []);
        $category = $data['category'] ?? 'Web Development';
        if (! in_array($category, $categories, true)) {
            $category = 'Web Development';
        }

        $title = trim((string) ($data['title'] ?? 'Untitled Project'));
        $slug = trim((string) ($data['slug'] ?? ''));
        if ($slug === '') {
            $slug = Str::slug($title);
        } else {
            $slug = Str::slug($slug);
        }

        return [
            'title' => Str::limit($title, 255, ''),
            'slug' => $slug,
            'short_description' => Str::limit(trim((string) ($data['short_description'] ?? '')), 500, ''),
            'description' => trim((string) ($data['description'] ?? '')),
            'technologies' => $this->normalizeStringArray($data['technologies'] ?? [], 100),
            'category' => $category,
            'features' => $this->normalizeStringArray($data['features'] ?? [], 200),
            'duration_months' => isset($data['duration_months']) && $data['duration_months'] !== ''
                ? (int) $data['duration_months']
                : null,
            'client' => isset($data['client']) ? trim((string) $data['client']) : null,
            'challenges' => isset($data['challenges']) ? trim((string) $data['challenges']) : null,
            'solutions' => isset($data['solutions']) ? trim((string) $data['solutions']) : null,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'is_published' => (bool) ($data['is_published'] ?? true),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'image_urls' => $this->normalizeStringArray($data['image_urls'] ?? [], 500),
        ];
    }

    /**
     * @return array{success: bool, data?: array, message?: string}
     */
    private function parsePortfolioJson(string $raw): array
    {
        $raw = trim($raw);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/i', $raw, $m)) {
            $raw = trim($m[1]);
        }

        $start = strpos($raw, '{');
        $end = strrpos($raw, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $raw = substr($raw, $start, $end - $start + 1);
        }

        try {
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return [
                'success' => false,
                'message' => 'Invalid JSON from model: '.$e->getMessage(),
            ];
        }

        if (! is_array($data)) {
            return ['success' => false, 'message' => 'Model did not return a JSON object.'];
        }

        return ['success' => true, 'data' => $data];
    }

    /**
     * @param  mixed  $value
     * @return list<string>
     */
    private function normalizeStringArray(mixed $value, int $maxLen): array
    {
        if (is_string($value)) {
            $value = array_map('trim', preg_split('/[\n,]+/', $value) ?: []);
        }

        if (! is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $item) {
            $s = trim((string) $item);
            if ($s !== '') {
                $out[] = Str::limit($s, $maxLen, '');
            }
        }

        return array_values(array_unique($out));
    }
}
