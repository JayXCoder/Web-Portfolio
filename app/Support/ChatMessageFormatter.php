<?php

namespace App\Support;

class ChatMessageFormatter
{
    /**
     * Safe subset of markdown for chat bubbles (bold, italic, inline code).
     */
    public static function toHtml(string $text): string
    {
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
