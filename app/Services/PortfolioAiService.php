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
        $userContent = "Convert the following markdown (possibly from multiple files merged) into exactly ONE portfolio JSON object:\n\n---\n".$markdown."\n---";

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
        $data = $this->coalesceFieldAliases($data);

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

        $portfolio = [
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
            'is_featured' => (bool) ($data['is_featured'] ?? $data['isFeatured'] ?? false),
            'is_published' => (bool) ($data['is_published'] ?? $data['isPublished'] ?? true),
            'sort_order' => (int) ($data['sort_order'] ?? $data['sortOrder'] ?? 0),
            'image_urls' => $this->normalizeStringArray($data['image_urls'] ?? $data['imageUrls'] ?? [], 500),
        ];

        return $this->ensureRequiredFields($portfolio);
    }

    /**
     * Map common model key variants (camelCase, synonyms) to schema fields.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function coalesceFieldAliases(array $data): array
    {
        $aliases = [
            'short_description' => ['shortDescription', 'short_desc', 'shortDesc', 'summary', 'excerpt', 'tagline', 'elevator_pitch'],
            'description' => ['desc', 'overview', 'body', 'content', 'details', 'case_study'],
            'technologies' => ['tech_stack', 'techStack', 'stack', 'tech', 'tools', 'skills_used'],
            'features' => ['key_features', 'keyFeatures', 'highlights', 'capabilities', 'bullets'],
            'duration_months' => ['durationMonths', 'duration', 'project_duration'],
            'image_urls' => ['imageUrls', 'images', 'screenshots'],
        ];

        foreach ($aliases as $canonical => $keys) {
            if ($this->hasMeaningfulValue($data[$canonical] ?? null)) {
                continue;
            }

            foreach ($keys as $key) {
                if ($this->hasMeaningfulValue($data[$key] ?? null)) {
                    $data[$canonical] = $data[$key];
                    break;
                }
            }
        }

        return $data;
    }

    private function hasMeaningfulValue(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $portfolio
     * @return array<string, mixed>
     */
    private function ensureRequiredFields(array $portfolio): array
    {
        $description = (string) ($portfolio['description'] ?? '');

        if ($description === '' && ($portfolio['short_description'] ?? '') !== '') {
            $portfolio['description'] = (string) $portfolio['short_description'];
            $description = $portfolio['description'];
        }

        if (($portfolio['short_description'] ?? '') === '' && $description !== '') {
            $plain = preg_replace('/\s+/', ' ', strip_tags($description)) ?: '';
            $portfolio['short_description'] = Str::limit($plain, 500, '');
        }

        if (($portfolio['technologies'] ?? []) === []) {
            $portfolio['technologies'] = $this->inferTechnologies($description);
        }

        if (($portfolio['features'] ?? []) === []) {
            $portfolio['features'] = $this->inferFeatures($description);
        }

        if (($portfolio['features'] ?? []) === [] && $description !== '') {
            $portfolio['features'] = [
                Str::limit(preg_replace('/\s+/', ' ', strip_tags($description)) ?: 'Project delivery', 200, ''),
            ];
        }

        if (($portfolio['technologies'] ?? []) === []) {
            $portfolio['technologies'] = ['General'];
        }

        return $portfolio;
    }

    /**
     * @return list<string>
     */
    private function inferTechnologies(string $description): array
    {
        if (preg_match_all('/`([^`]+)`|\*\*([^*]+)\*\*/', $description, $matches)) {
            $candidates = array_merge($matches[1], $matches[2]);
            $tech = $this->normalizeStringArray(array_filter($candidates), 100);

            if ($tech !== []) {
                return $tech;
            }
        }

        if (preg_match('/(?:technologies?|stack|built with)[:\s]+([^\n.]+)/i', $description, $match)) {
            $tech = $this->normalizeStringArray($match[1], 100);

            if ($tech !== []) {
                return $tech;
            }
        }

        return [];
    }

    /**
     * @return list<string>
     */
    private function inferFeatures(string $description): array
    {
        $features = [];

        foreach (preg_split('/\r\n|\r|\n/', $description) ?: [] as $line) {
            $line = trim($line);

            if (preg_match('/^[-*•]\s+(.+)$/u', $line, $match)) {
                $features[] = Str::limit($match[1], 200, '');
            }
        }

        return $this->normalizeStringArray($features, 200);
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
