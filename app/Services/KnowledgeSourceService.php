<?php

namespace App\Services;

use App\Jobs\IndexKnowledgeDocument;
use App\Models\Achievement;
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
            ? ['profile', 'skills', 'portfolio', 'achievement', 'experience']
            : [$source];
        $totals = ['seen' => 0, 'changed' => 0, 'deactivated' => 0];

        foreach ($types as $type) {
            $result = $this->syncDocuments($type, $this->documentsFor($type), true, $queue, $force);
            foreach ($totals as $key => $value) {
                $totals[$key] += $result[$key];
            }
        }

        return $totals;
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
            'achievement' => Achievement::published()->ordered()->get()->map(fn (Achievement $a) => $this->achievementDocument($a))->all(),
            'experience' => WorkExperience::published()->ordered()->get()->map(fn (WorkExperience $w) => $this->experienceDocument($w))->all(),
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

    private function achievementDocument(Achievement $a): array
    {
        return [
            'source_key' => (string) $a->id,
            'title' => $a->title,
            'content' => implode("\n", array_filter([
                'Achievement: '.$a->title,
                'Type: '.$a->typeLabel(),
                'Organization: '.$a->organization,
                $a->placement ? 'Placement: '.$a->placement : null,
                $a->location ? 'Location: '.$a->location : null,
                'Story: '.$a->story,
                $a->project ? 'Project: '.$a->project : null,
                'Skills: '.implode(', ', $a->skills ?? []),
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

    private function hashPayload(array $payload): string
    {
        return hash('sha256', json_encode([
            $payload['title'], $payload['content'], $payload['url'],
            $payload['metadata'], $payload['published_at'], $payload['is_active'],
        ], JSON_THROW_ON_ERROR));
    }
}
