<?php

namespace App\Support;

class ChatMessageFormatter
{
    /**
     * Remove project-link boilerplate the model should not show (buttons are rendered separately).
     */
    public static function sanitizeReply(string $text): string
    {
        $text = preg_replace('/^[\s\*\-•]*View project:\s*.*$(\r?\n)?/mi', '', $text) ?? $text;
        $text = preg_replace('/\s*\(slug:\s*[a-z0-9]+(?:-[a-z0-9]+)*\)/i', '', $text) ?? $text;
        $text = preg_replace('/\bslug:\s*[a-z0-9]+(?:-[a-z0-9]+)*/i', '', $text) ?? $text;
        $text = preg_replace('/\s*Use the buttons below[^\n.]*[.\n]?/i', '', $text) ?? $text;
        $text = preg_replace('/\s*Project links appear as buttons[^\n.]*[.\n]?/i', '', $text) ?? $text;
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

        return trim($text);
    }

    /**
     * Pull portfolio slugs from raw model output before sanitization (for button list).
     *
     * @return list<string>
     */
    public static function extractProjectSlugs(string $text): array
    {
        if (preg_match_all('/\(slug:\s*([a-z0-9]+(?:-[a-z0-9]+)*)\)/i', $text, $matches)) {
            return array_values(array_unique($matches[1]));
        }

        return [];
    }

    /**
     * Safe subset of markdown for chat bubbles (bold, italic, inline code).
     */
    public static function toHtml(string $text): string
    {
        $text = self::sanitizeReply($text);
        $text = e($text);

        $text = (string) preg_replace(
            '/\*\*(.+?)\*\*/s',
            '<strong class="font-semibold text-text">$1</strong>',
            $text
        );

        $text = (string) preg_replace(
            '/(?<!\*)\*([^*\n]+)\*(?!\*)/',
            '<em class="text-text-muted">$1</em>',
            $text
        );

        $text = (string) preg_replace(
            '/`([^`\n]+)`/',
            '<code class="rounded bg-oled/60 px-1 py-0.5 text-uv-bright text-xs">$1</code>',
            $text
        );

        return nl2br($text, false);
    }
}
