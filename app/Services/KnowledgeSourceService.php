<?php

namespace App\Services;

use App\Jobs\IndexKnowledgeDocument;
use App\Models\Achievement;
use App\Models\BlogPost;
use App\Models\KnowledgeDocument;
use App\Models\KnowledgeSyncRun;
use App\Models\Portfolio;
use App\Models\WorkExperience;
use Illuminate\Support\Arr;

class KnowledgeSourceService
{
    public function __construct(private KnowledgeIndexer $indexer) {}

    /**
     * @return array{seen: int, changed: int, deactivated: int}
     */
    public function refresh(string $source = 'all', bool $queue = true, bool $force = false): array
    {
        $types = $source === 'all'
            ? ['profile', 'skills', 'portfolio', 'achievement', 'experience', 'blog', 'linkedin_post']
            : [$source];
        $totals = ['seen' => 0, 'changed' => 0, 'deactivated' => 0];

        foreach ($types as $type) {
            // LinkedIn posts come from OAuth/export imports, not portfolio tables.
            // Reindex existing rows; never full-reconcile from an empty documentsFor().
            $result = $type === 'linkedin_post'
                ? $this->reindexExisting($type, $queue, $force)
                : $this->syncDocuments($type, $this->documentsFor($type), true, $queue, $force);
            foreach ($totals as $key => $value) {
                $totals[$key] += $result[$key];
            }
        }

        return $totals;
    }

    /**
     * Force-index already imported documents without reconciling source keys away.
     *
     * @return array{seen: int, changed: int, deactivated: int}
     */
    public function reindexExisting(string $sourceType, bool $queue = true, bool $force = false): array
    {
        $run = KnowledgeSyncRun::create([
            'source_type' => $sourceType,
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $documents = KnowledgeDocument::query()
                ->where('source_type', $sourceType)
                ->where('is_active', true)
                ->get();
            $changed = 0;
            foreach ($documents as $document) {
                $needsIndex = $force
                    || ! $document->last_indexed_at
                    || $document->last_error;
                if (! $needsIndex) {
                    continue;
                }
                $changed++;
                $queue
                    ? IndexKnowledgeDocument::dispatch($document->id)
                    : $this->indexer->index($document);
            }

            $run->update([
                'status' => 'completed',
                'documents_seen' => $documents->count(),
                'documents_changed' => $changed,
                'documents_deactivated' => 0,
                'finished_at' => now(),
            ]);

            return ['seen' => $documents->count(), 'changed' => $changed, 'deactivated' => 0];
        } catch (\Throwable $e) {
            $run->update([
                'status' => 'failed',
                'error' => mb_substr($e->getMessage(), 0, 2000),
                'finished_at' => now(),
            ]);
            throw $e;
        }
    }

    /**
     * @param  list<array<string, mixed>>  $documents
     * @return array{seen: int, changed: int, deactivated: int}
     */
    public function syncDocuments(
        string $sourceType,
        array $documents,
        bool $fullReconcile = false,
        bool $queue = true,
        bool $force = false,
    ): array {
        $run = KnowledgeSyncRun::create([
            'source_type' => $sourceType,
            'status' => 'running',
            'started_at' => now(),
        ]);
        $seen = [];
        $changed = 0;

        try {
            foreach ($documents as $payload) {
                $payload['source_type'] = $sourceType;
                $payload['metadata'] = $payload['metadata'] ?? [];
                $payload['url'] = $payload['url'] ?? null;
                $payload['published_at'] = $payload['published_at'] ?? null;
                $payload['is_active'] = $payload['is_active'] ?? true;
                $payload['content_hash'] = $this->hashPayload($payload);
                $seen[] = (string) $payload['source_key'];

                $document = KnowledgeDocument::firstOrNew([
                    'source_type' => $sourceType,
                    'source_key' => $payload['source_key'],
                ]);
                $needsIndex = $force
                    || ! $document->exists
                    || $document->content_hash !== $payload['content_hash']
                    || ! $document->last_indexed_at
                    || $document->last_error;

                $document->fill(Arr::only($payload, [
                    'source_type', 'source_key', 'title', 'content', 'url', 'metadata',
                    'published_at', 'content_hash', 'is_active',
                ]));
                $document->save();

                if ($needsIndex && $document->is_active) {
                    $changed++;
                    $queue
                        ? IndexKnowledgeDocument::dispatch($document->id)
                        : $this->indexer->index($document);
                } elseif (! $document->is_active) {
                    $document->chunks()->delete();
                }
            }

            $deactivated = 0;
            if ($fullReconcile) {
                $query = KnowledgeDocument::where('source_type', $sourceType)->where('is_active', true);
                $seen === [] ? $query->whereNotNull('id') : $query->whereNotIn('source_key', $seen);
                $stale = $query->get();
                foreach ($stale as $document) {
                    $document->update(['is_active' => false]);
                    $document->chunks()->delete();
                    $deactivated++;
                }
            }

            $run->update([
                'status' => 'completed',
                'documents_seen' => count($documents),
                'documents_changed' => $changed,
                'documents_deactivated' => $deactivated,
                'finished_at' => now(),
            ]);

            return ['seen' => count($documents), 'changed' => $changed, 'deactivated' => $deactivated];
        } catch (\Throwable $e) {
            $run->update([
                'status' => 'failed',
                'documents_seen' => count($seen),
                'documents_changed' => $changed,
                'error' => mb_substr($e->getMessage(), 0, 2000),
                'finished_at' => now(),
            ]);
            throw $e;
        }
    }

    /** @return list<array<string, mixed>> */
    public function documentsFor(string $type): array
    {
        return match ($type) {
            'profile' => [$this->profileDocument()],
            'skills' => [$this->skillsDocument()],
            'portfolio' => Portfolio::published()->ordered()->get()->map(fn (Portfolio $p) => $this->portfolioDocument($p))->all(),
            'achievement' => $this->achievementDocuments(),
            'experience' => WorkExperience::published()->ordered()->get()->map(fn (WorkExperience $w) => $this->experienceDocument($w))->all(),
            'blog' => BlogPost::published()->ordered()->get()->map(fn (BlogPost $p) => $this->blogDocument($p))->all(),
            default => [],
        };
    }

    private function profileDocument(): array
    {
        return [
            'source_key' => 'jay-profile',
            'title' => config('rag.profile.title'),
            'content' => config('rag.profile.content'),
            'url' => route('about'),
            'metadata' => ['label' => 'Profile'],
        ];
    }

    private function skillsDocument(): array
    {
        $groups = [];
        foreach (config('skills.ai_tools', []) as $skill) {
            $groups['Productive AI tools'][] = $skill['label'];
        }
        $tree = config('skills.tree', []);
        foreach (['apex' => 'Full Stack', 'stacks' => 'Stacks and UI', 'foundation' => 'Languages'] as $key => $label) {
            foreach ($tree[$key]['skills'] ?? [] as $skill) {
                $groups[$label][] = $skill['label'];
            }
        }
        foreach ($tree['branches'] ?? [] as $branch) {
            foreach ($branch['skills'] ?? [] as $skill) {
                $groups[$branch['title'] ?? 'Other'][] = $skill['label'];
            }
        }
        $lines = ['Jay has hands-on experience with every technology listed below.'];
        foreach ($groups as $group => $skills) {
            $lines[] = $group.': '.implode(', ', array_unique($skills)).'.';
        }

        return [
            'source_key' => 'skills-page',
            'title' => 'Skills and technologies',
            'content' => implode("\n", $lines),
            'url' => route('skills'),
            'metadata' => ['label' => 'Skills'],
        ];
    }

    private function portfolioDocument(Portfolio $p): array
    {
        $content = array_filter([
            'Project: '.$p->title,
            'Category: '.$p->category,
            'Summary: '.$p->short_description,
            'Description: '.$p->description,
            'Technologies: '.implode(', ', $p->technologies ?? []),
            'Features: '.implode('; ', $p->features ?? []),
            $p->challenges ? 'Challenges: '.$p->challenges : null,
            $p->solutions ? 'Solutions: '.$p->solutions : null,
        ]);

        return [
            'source_key' => (string) $p->id,
            'title' => $p->title,
            'content' => implode("\n", $content),
            'url' => route('portfolio.item', $p),
            'published_at' => $p->created_at,
            'metadata' => ['slug' => $p->slug, 'category' => $p->category],
        ];
    }

    /** @return list<array<string, mixed>> */
    private function achievementDocuments(): array
    {
        $achievements = Achievement::published()->ordered()->get();
        if ($achievements->isEmpty()) {
            return [];
        }

        $lines = [
            'Jay\'s published achievements, awards, certificates, and credentials are listed below.',
            'These come from the Achievements page on the portfolio site.',
        ];
        foreach ($achievements as $achievement) {
            $lines[] = sprintf(
                '- %s (%s) from %s%s',
                $achievement->title,
                $achievement->typeLabel(),
                $achievement->organization ?: 'unspecified organization',
                $achievement->issued_date ? ' on '.$achievement->issued_date->format('Y-m-d') : '',
            );
        }

        $documents = [[
            'source_key' => 'achievements-overview',
            'title' => 'Achievements, awards, and certificates',
            'content' => implode("\n", $lines),
            'url' => route('achievements'),
            'metadata' => ['label' => 'Achievements overview'],
        ]];

        foreach ($achievements as $achievement) {
            $documents[] = $this->achievementDocument($achievement);
        }

        return $documents;
    }

    private function achievementDocument(Achievement $a): array
    {
        return [
            'source_key' => (string) $a->id,
            'title' => $a->title,
            'content' => implode("\n", array_filter([
                'Achievement / award / certificate / credential title: '.$a->title,
                'Type: '.$a->typeLabel(),
                'Organization / issuer: '.$a->organization,
                $a->placement ? 'Placement / result: '.$a->placement : null,
                $a->location ? 'Location: '.$a->location : null,
                $a->issued_date ? 'Issued date: '.$a->issued_date->format('Y-m-d') : null,
                'Story: '.$a->story,
                $a->project ? 'Related project: '.$a->project : null,
                'Skills: '.implode(', ', $a->skills ?? []),
                $a->credly_url ? 'Credly verification available for this credential.' : null,
            ])),
            'url' => route('achievements'),
            'published_at' => $a->issued_date,
            'metadata' => ['achievement_type' => $a->type],
        ];
    }

    private function experienceDocument(WorkExperience $w): array
    {
        return [
            'source_key' => (string) $w->id,
            'title' => $w->position.' at '.$w->company,
            'content' => implode("\n", array_filter([
                'Role: '.$w->position,
                'Company: '.$w->company,
                'Employment type: '.$w->employment_type,
                'Period: '.$w->start_date?->format('Y-m').' to '.($w->is_current ? 'Present' : $w->end_date?->format('Y-m')),
                $w->location ? 'Location: '.$w->location : null,
                'Description: '.$w->description,
                $w->responsibilities ? 'Responsibilities: '.$w->responsibilities : null,
                'Technologies: '.implode(', ', $w->technologies ?? []),
                'Achievements: '.implode('; ', $w->achievements ?? []),
                'Skills gained: '.implode(', ', $w->skills_gained ?? []),
            ])),
            'url' => route('experience'),
            'published_at' => $w->start_date,
            'metadata' => ['company' => $w->company, 'current' => $w->is_current],
        ];
    }

    private function blogDocument(BlogPost $post): array
    {
        return [
            'source_key' => (string) $post->id,
            'title' => $post->title,
            'content' => implode("\n", array_filter([
                'Blog post: '.$post->title,
                'Author: '.$post->author_name,
                $post->excerpt ? 'Excerpt: '.$post->excerpt : null,
                'Body: '.$post->body,
                ! empty($post->tags) ? 'Tags: '.implode(', ', $post->tags) : null,
            ])),
            'url' => route('blog.show', $post->slug),
            'published_at' => $post->published_at ?? $post->created_at,
            'metadata' => ['slug' => $post->slug, 'author' => $post->author_name],
        ];
    }

    private function hashPayload(array $payload): string
    {
        return hash('sha256', json_encode([
            $payload['title'], $payload['content'], $payload['url'],
            $payload['metadata'], $payload['published_at'], $payload['is_active'],
        ], JSON_THROW_ON_ERROR));
    }
}
