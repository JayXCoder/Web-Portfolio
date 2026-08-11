<?php

namespace Tests\Unit;

use App\Support\MarkdownRenderer;
use PHPUnit\Framework\TestCase;

class MarkdownRendererTest extends TestCase
{
    public function test_renders_fenced_code_and_tables(): void
    {
        $md = <<<'MD'
# Hello

| A | B |
| - | - |
| 1 | 2 |

```php
echo "hi";
```
MD;

        $html = MarkdownRenderer::toHtml($md);

        $this->assertStringContainsString('<h1>', $html);
        $this->assertStringContainsString('<table>', $html);
        $this->assertStringContainsString('language-php', $html);
        $this->assertStringContainsString('echo &quot;hi&quot;;', $html);
    }

    public function test_mermaid_fence_becomes_pre_mermaid(): void
    {
        $html = MarkdownRenderer::toHtml("```mermaid\ngraph TD\nA-->B\n```");

        $this->assertStringContainsString('class="mermaid"', $html);
        $this->assertStringContainsString('graph TD', $html);
    }

    public function test_strips_raw_html(): void
    {
        $html = MarkdownRenderer::toHtml('Hello <script>alert(1)</script> world');

        $this->assertStringNotContainsString('<script>', $html);
    }
}
