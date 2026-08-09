<?php

namespace App\Services;

class KnowledgeChunker
{
    /** @return list<string> */
    public function chunk(string $content): array
    {
        $content = trim((string) preg_replace('/\r\n?/', "\n", $content));
        if ($content === '') {
            return [];
        }

        $size = max(300, (int) config('rag.chunk_size', 1200));
        $overlap = min(max(0, (int) config('rag.chunk_overlap', 150)), $size - 1);
        $chunks = [];
        $offset = 0;
        $length = mb_strlen($content);

        while ($offset < $length) {
            $take = min($size, $length - $offset);
            $chunk = mb_substr($content, $offset, $take);

            if ($offset + $take < $length) {
                $breakAt = max(
                    (int) mb_strrpos($chunk, "\n\n"),
                    (int) mb_strrpos($chunk, '. '),
                    (int) mb_strrpos($chunk, ' ')
                );
                if ($breakAt >= (int) ($size * 0.6)) {
                    $chunk = mb_substr($chunk, 0, $breakAt + 1);
                    $take = mb_strlen($chunk);
                }
            }

            $chunk = trim($chunk);
            if ($chunk !== '') {
                $chunks[] = $chunk;
            }

            if ($offset + $take >= $length) {
                break;
            }
            $offset += max(1, $take - $overlap);
        }

        return array_values(array_unique($chunks));
    }
}
