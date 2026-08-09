<?php

namespace App\Services;

use App\Models\KnowledgeChunk;
use RuntimeException;

class KnowledgeRetrievalService
{
    public function __construct(private RagOllamaService $ollama) {}

    /**
     * @param  list<string>  $queries
     * @param  list<string>  $sourceTypes
     * @return list<array{chunk: KnowledgeChunk, semantic_score: float, keyword_score: float, score: float}>
     */
    public function retrieve(array $queries, array $sourceTypes = []): array
    {
        $queries = array_values(array_filter(array_unique(array_map('trim', $queries))));
        if ($queries === []) {
            return [];
        }

        $result = $this->ollama->embed($queries);
        if (! $result['success']) {
            throw new RuntimeException($result['error'] ?? 'Query embedding failed.');
        }

        $queryVectors = $result['embeddings'];
        $terms = $this->terms(implode(' ', $queries));
        $query = KnowledgeChunk::query()
            ->with('document')
            ->whereHas('document', fn ($q) => $q->where('is_active', true));
        if ($sourceTypes !== []) {
            $query->whereHas('document', fn ($q) => $q->whereIn('source_type', $sourceTypes)->where('is_active', true));
        }

        $minimum = (float) config('rag.min_semantic_score', 0.35);
        $ranked = [];
        foreach ($query->get() as $chunk) {
            $vector = $chunk->vector();
            if ($vector === []) {
                continue;
            }
            $semantic = max(array_map(fn (array $queryVector) => $this->cosine($queryVector, $vector), $queryVectors));
            $keyword = $this->keywordScore($terms, $chunk->content);
            $score = ($semantic * 0.75) + ($keyword * 0.25);
            if (($semantic < $minimum && $keyword < 0.2) || $score < ($minimum - 0.05)) {
                continue;
            }
            $ranked[] = [
                'chunk' => $chunk,
                'semantic_score' => $semantic,
                'keyword_score' => $keyword,
                'score' => $score,
            ];
        }

        usort($ranked, fn ($a, $b) => $b['score'] <=> $a['score']);
        $selected = [];
        $perDocument = [];
        foreach ($ranked as $row) {
            $documentId = $row['chunk']->knowledge_document_id;
            if (($perDocument[$documentId] ?? 0) >= (int) config('rag.max_chunks_per_document', 2)) {
                continue;
            }
            $selected[] = $row;
            $perDocument[$documentId] = ($perDocument[$documentId] ?? 0) + 1;
            if (count($selected) >= (int) config('rag.top_k', 8)) {
                break;
            }
        }

        return $selected;
    }

    /** @param list<float|int> $left @param list<float|int> $right */
    public function cosine(array $left, array $right): float
    {
        if (count($left) !== count($right) || $left === []) {
            return 0.0;
        }
        $dot = $leftNorm = $rightNorm = 0.0;
        foreach ($left as $index => $value) {
            $l = (float) $value;
            $r = (float) $right[$index];
            $dot += $l * $r;
            $leftNorm += $l * $l;
            $rightNorm += $r * $r;
        }
        if ($leftNorm <= 0 || $rightNorm <= 0) {
            return 0.0;
        }

        return $dot / (sqrt($leftNorm) * sqrt($rightNorm));
    }

    /** @return list<string> */
    private function terms(string $text): array
    {
        preg_match_all('/[a-z0-9+#.]{2,}/i', mb_strtolower($text), $matches);
        $stopWords = ['what', 'which', 'who', 'how', 'has', 'have', 'with', 'from', 'about', 'mention', 'supporting', 'jay', 'jayxcoder'];
        $terms = array_values(array_diff($matches[0] ?? [], $stopWords));
        $expanded = $terms;
        foreach ($terms as $term) {
            if (mb_strlen($term) > 4 && str_ends_with($term, 's')) {
                $expanded[] = mb_substr($term, 0, -1);
            }
            if ($term === 'cybersecurity') {
                $expanded[] = 'cyber';
                $expanded[] = 'security';
            }
        }

        return array_values(array_unique($expanded));
    }

    /** @param list<string> $terms */
    private function keywordScore(array $terms, string $content): float
    {
        if ($terms === []) {
            return 0.0;
        }
        $haystack = mb_strtolower($content);
        $matches = count(array_filter($terms, fn ($term) => str_contains($haystack, $term)));

        return $matches / count($terms);
    }
}
