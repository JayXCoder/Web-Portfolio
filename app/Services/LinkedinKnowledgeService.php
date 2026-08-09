<?php

namespace App\Services;

use App\Models\LinkedinConnection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LinkedinKnowledgeService
{
    public function __construct(private KnowledgeSourceService $sources) {}

    /** @return array{seen: int, changed: int, deactivated: int} */
    public function sync(LinkedinConnection $connection, bool $full = true, bool $queue = true): array
    {
        if ($connection->token_expires_at?->isPast()) {
            $connection->update(['status' => 'reauthorization_required', 'last_error' => 'LinkedIn access token expired.']);
            throw new RuntimeException('LinkedIn authorization has expired.');
        }
        if (! $connection->member_urn) {
            throw new RuntimeException('LinkedIn member identifier is missing.');
        }

        $documents = [];
        $start = 0;
        $count = 100;

        try {
            do {
                $response = Http::withToken($connection->access_token)
                    ->withHeaders([
                        'Linkedin-Version' => config('linkedin.api_version'),
                        'X-Restli-Protocol-Version' => '2.0.0',
                    ])
                    ->timeout((int) config('linkedin.timeout', 30))
                    ->retry(3, fn (int $attempt) => $attempt * 750, throw: false)
                    ->get(config('linkedin.api_url').'/posts', [
                        'q' => 'author',
                        'author' => $connection->member_urn,
                        'start' => $start,
                        'count' => $count,
                    ]);

                if ($response->status() === 401 || $response->status() === 403) {
                    $connection->update([
                        'status' => 'reauthorization_required',
                        'last_error' => 'LinkedIn rejected the authorization token or required permission.',
                    ]);
                    throw new RuntimeException('LinkedIn authorization is invalid or missing r_member_social.');
                }
                if (! $response->successful()) {
                    throw new RuntimeException('LinkedIn Posts API failed with HTTP '.$response->status().'.');
                }

                $elements = $response->json('elements', []);
                foreach ($elements as $post) {
                    if (($post['lifecycleState'] ?? 'PUBLISHED') !== 'PUBLISHED') {
                        continue;
                    }
                    $documents[] = $this->postDocument($post);
                }
                $start += count($elements);
                $total = (int) data_get($response->json(), 'paging.total', $start);
            } while ($elements !== [] && $start < $total);

            $result = $this->sources->syncDocuments('linkedin_post', $documents, $full, $queue);
            $connection->update([
                'status' => 'connected',
                'last_synced_at' => now(),
                'last_error' => null,
            ]);

            return $result;
        } catch (\Throwable $e) {
            if ($connection->status !== 'reauthorization_required') {
                $connection->update(['status' => 'error', 'last_error' => mb_substr($e->getMessage(), 0, 2000)]);
            }
            throw $e;
        }
    }

    /** @return array<string, mixed> */
    private function postDocument(array $post): array
    {
        $id = (string) ($post['id'] ?? $post['activity'] ?? hash('sha256', json_encode($post)));
        $commentary = $this->text($post['commentary'] ?? '');
        $article = $post['content']['article'] ?? [];
        $articleTitle = $this->text($article['title'] ?? '');
        $articleDescription = $this->text($article['description'] ?? '');
        $content = implode("\n", array_filter([
            'LinkedIn post by Jay.',
            $commentary !== '' ? 'Post: '.$commentary : null,
            $articleTitle !== '' ? 'Shared article: '.$articleTitle : null,
            $articleDescription !== '' ? 'Article description: '.$articleDescription : null,
        ]));
        $timestamp = $post['publishedAt'] ?? $post['createdAt'] ?? null;

        return [
            'source_key' => $id,
            'title' => $articleTitle !== '' ? $articleTitle : $this->title($commentary),
            'content' => $content,
            'url' => 'https://www.linkedin.com/feed/update/'.rawurlencode($id).'/',
            'published_at' => is_numeric($timestamp) ? Carbon::createFromTimestampMs((int) $timestamp) : null,
            'metadata' => ['linkedin_urn' => $id, 'origin' => 'api'],
        ];
    }

    private function text(mixed $value): string
    {
        if (is_string($value)) {
            return trim($value);
        }
        if (is_array($value)) {
            return trim((string) ($value['text'] ?? data_get($value, 'localized.en_US', '')));
        }

        return '';
    }

    private function title(string $commentary): string
    {
        $title = trim((string) preg_replace('/\s+/', ' ', $commentary));

        return $title === '' ? 'LinkedIn post' : mb_strimwidth($title, 0, 100, '…');
    }
}
