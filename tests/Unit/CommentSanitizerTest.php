<?php

namespace Tests\Unit;

use App\Support\CommentSanitizer;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CommentSanitizerTest extends TestCase
{
    public function test_strips_script_and_html_tags(): void
    {
        $dirty = '<script>alert("xss")</script>Hello <b>there</b>';
        $this->assertSame('Hello there', CommentSanitizer::body($dirty));
    }

    public function test_decodes_entities_then_strips_tags(): void
    {
        $dirty = '&lt;img src=x onerror=alert(1)&gt;hi';
        $this->assertSame('hi', CommentSanitizer::body($dirty));
    }

    public function test_removes_null_bytes_and_control_chars(): void
    {
        $dirty = "Hi\0\x08 there\n\nok";
        $this->assertSame("Hi there\n\nok", CommentSanitizer::body($dirty));
    }

    public function test_author_name_collapses_whitespace_and_blocks_newlines(): void
    {
        $this->assertSame('Jay Coder', CommentSanitizer::authorName("  Jay\nCoder  "));
    }

    public function test_email_normalized_or_null(): void
    {
        $this->assertSame('a@b.com', CommentSanitizer::email('  A@B.COM '));
        $this->assertNull(CommentSanitizer::email('   '));
        $this->assertNull(CommentSanitizer::email(null));
    }

    #[DataProvider('markupProvider')]
    public function test_common_xss_payloads_become_plain_text(string $input, string $expected): void
    {
        $this->assertSame($expected, CommentSanitizer::body($input));
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function markupProvider(): array
    {
        return [
            'svg' => ['<svg/onload=alert(1)>nope', 'nope'],
            'iframe' => ['<iframe src="javascript:alert(1)"></iframe>safe', 'safe'],
            'event handler text survives as text' => ['onclick=alert(1) still words', 'onclick=alert(1) still words'],
            'nested tags' => ['<div><a href="javascript:alert(1)">click</a></div>', 'click'],
        ];
    }
}
