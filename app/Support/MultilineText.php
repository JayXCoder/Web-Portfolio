<?php

namespace App\Support;

class MultilineText
{
    /**
     * Turn plain text with newlines and "- " bullet lines into safe HTML.
     */
    public static function toHtml(string $text): string
    {
        $text = trim(str_replace(["\r\n", "\r"], "\n", $text));

        if ($text === '') {
            return '';
        }

        $html = '';
        $inList = false;
        $paragraphLines = [];

        foreach (explode("\n", $text) as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                self::flushParagraph($html, $paragraphLines);
                self::closeList($html, $inList);

                continue;
            }

            if (preg_match('/^[-*•]\s+(.+)$/u', $trimmed, $matches)) {
                self::flushParagraph($html, $paragraphLines);

                if (! $inList) {
                    $html .= '<ul class="mt-2 list-disc space-y-1.5 pl-5">';
                    $inList = true;
                }

                $html .= '<li>'.e($matches[1]).'</li>';

                continue;
            }

            self::closeList($html, $inList);
            $paragraphLines[] = $line;
        }

        self::flushParagraph($html, $paragraphLines);
        self::closeList($html, $inList);

        return $html;
    }

    /**
     * @param  list<string>  $paragraphLines
     */
    private static function flushParagraph(string &$html, array &$paragraphLines): void
    {
        if ($paragraphLines === []) {
            return;
        }

        $html .= '<p class="leading-relaxed whitespace-pre-line">'.e(implode("\n", $paragraphLines)).'</p>';
        $paragraphLines = [];
    }

    private static function closeList(string &$html, bool &$inList): void
    {
        if (! $inList) {
            return;
        }

        $html .= '</ul>';
        $inList = false;
    }
}
