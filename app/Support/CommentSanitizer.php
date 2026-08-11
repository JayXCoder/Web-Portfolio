<?php

namespace App\Support;

class CommentSanitizer
{
    /**
     * Plain-text only: strip markup, null bytes, and control characters.
     * Keeps normal Unicode letters so international names still work.
     */
    public static function plainText(string $value, int $maxLength = 5000, bool $allowNewlines = true): string
    {
        $value = str_replace("\0", '', $value);
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove executable / embed blocks entirely (including their inner text).
        $value = preg_replace(
            '/<(script|style|iframe|object|embed|svg|link|meta)\b[^>]*>.*?<\/\1>/is',
            '',
            $value
        ) ?? $value;
        $value = preg_replace(
            '/<(script|style|iframe|object|embed|svg|link|meta)\b[^>]*\/?>/is',
            '',
            $value
        ) ?? $value;

        // Neutralize inline event handlers and javascript: URLs left in attributes
        // before strip_tags removes the tags themselves.
        $value = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $value) ?? $value;
        $value = preg_replace('/\s(href|src|xlink:href)\s*=\s*([\'"])\s*javascript:[^\'"]*\2/i', '', $value) ?? $value;

        $value = strip_tags($value);

        if ($allowNewlines) {
            $value = str_replace(["\r\n", "\r"], "\n", $value);
            $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
            $value = preg_replace("/[ \t]+\n/", "\n", $value) ?? $value;
            $value = preg_replace("/\n{3,}/", "\n\n", $value) ?? $value;
        } else {
            $value = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $value) ?? '';
            $value = preg_replace('/\s+/u', ' ', $value) ?? '';
        }

        $value = trim($value);

        if (mb_strlen($value) > $maxLength) {
            $value = mb_substr($value, 0, $maxLength);
        }

        return $value;
    }

    public static function authorName(string $value): string
    {
        return self::plainText($value, 120, allowNewlines: false);
    }

    public static function body(string $value): string
    {
        return self::plainText($value, 5000, allowNewlines: true);
    }

    public static function email(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = self::plainText($value, 255, allowNewlines: false);

        return $value === '' ? null : mb_strtolower($value);
    }
}
