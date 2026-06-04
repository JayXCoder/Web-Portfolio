<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PortfolioAiJobStore
{
    public function cacheKey(string $jobId): string
    {
        return 'portfolio-ai-job:'.$jobId;
    }

    public function create(string $markdown): string
    {
        $jobId = (string) Str::uuid();
        $ttl = now()->addMinutes((int) config('portfolio-ai.job_ttl_minutes', 120));

        Cache::put($this->cacheKey($jobId), [
            'status' => 'processing',
            'success' => false,
            'message' => 'Generating portfolio with Ollama…',
            'portfolio' => null,
            'markdown' => $markdown,
            'updated_at' => now()->toIso8601String(),
        ], $ttl);

        return $jobId;
    }

    public function getMarkdown(string $jobId): ?string
    {
        $data = Cache::get($this->cacheKey($jobId));

        if (! is_array($data)) {
            return null;
        }

        $markdown = $data['markdown'] ?? null;

        return is_string($markdown) && $markdown !== '' ? $markdown : null;
    }

    /**
     * @param  array{success: bool, message?: string, portfolio?: array<string, mixed>|null}  $result
     */
    public function finish(string $jobId, array $result): void
    {
        $existing = Cache::get($this->cacheKey($jobId));

        if (! is_array($existing)) {
            return;
        }

        $ttl = now()->addMinutes((int) config('portfolio-ai.job_ttl_minutes', 120));

        Cache::put($this->cacheKey($jobId), [
            'status' => ($result['success'] ?? false) ? 'completed' : 'failed',
            'success' => (bool) ($result['success'] ?? false),
            'message' => (string) ($result['message'] ?? ''),
            'portfolio' => $result['portfolio'] ?? null,
            'markdown' => $existing['markdown'] ?? null,
            'updated_at' => now()->toIso8601String(),
        ], $ttl);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function publicStatus(string $jobId): ?array
    {
        $data = Cache::get($this->cacheKey($jobId));

        if (! is_array($data)) {
            return null;
        }

        unset($data['markdown']);

        return $data;
    }
}
