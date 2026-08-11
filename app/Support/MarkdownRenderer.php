<?php

namespace App\Support;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

class MarkdownRenderer
{
    private static ?MarkdownConverter $converter = null;

    public static function toHtml(string $markdown): string
    {
        return (string) self::converter()->convert($markdown);
    }

    private static function converter(): MarkdownConverter
    {
        if (self::$converter !== null) {
            return self::$converter;
        }

        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 100,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);

        $environment->addRenderer(FencedCode::class, new class implements NodeRendererInterface
        {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
            {
                FencedCode::assertInstanceOf($node);

                $infoWords = $node->getInfoWords();
                $lang = $infoWords[0] ?? '';
                $content = $node->getLiteral();

                if (strtolower($lang) === 'mermaid') {
                    return new HtmlElement(
                        'pre',
                        ['class' => 'mermaid'],
                        Xml::escape($content)
                    );
                }

                $attrs = ['class' => 'language-'.($lang !== '' ? Xml::escape($lang) : 'plaintext')];
                if ($lang !== '') {
                    $attrs['data-lang'] = Xml::escape($lang);
                }

                $code = new HtmlElement('code', $attrs, Xml::escape($content));

                return new HtmlElement('pre', ['class' => 'md-code-block'], $code);
            }
        }, 100);

        self::$converter = new MarkdownConverter($environment);

        return self::$converter;
    }
}
