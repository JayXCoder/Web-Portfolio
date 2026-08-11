import DOMPurify from 'dompurify';

let mermaidReady = false;
let hljsReady = false;

async function loadHljs() {
    if (hljsReady) return hljsReady;
    const hljs = (await import('highlight.js/lib/core')).default;
    const langs = await Promise.all([
        import('highlight.js/lib/languages/javascript'),
        import('highlight.js/lib/languages/typescript'),
        import('highlight.js/lib/languages/php'),
        import('highlight.js/lib/languages/python'),
        import('highlight.js/lib/languages/bash'),
        import('highlight.js/lib/languages/json'),
        import('highlight.js/lib/languages/sql'),
        import('highlight.js/lib/languages/xml'),
        import('highlight.js/lib/languages/css'),
        import('highlight.js/lib/languages/rust'),
        import('highlight.js/lib/languages/go'),
        import('highlight.js/lib/languages/java'),
        import('highlight.js/lib/languages/c'),
        import('highlight.js/lib/languages/cpp'),
        import('highlight.js/lib/languages/yaml'),
        import('highlight.js/lib/languages/markdown'),
        import('highlight.js/lib/languages/plaintext'),
    ]);

    const [
        javascript, typescript, php, python, bash, json, sql, xml, css, rust, go, java, c, cpp, yaml, markdown, plaintext,
    ] = langs.map((m) => m.default);

    hljs.registerLanguage('javascript', javascript);
    hljs.registerLanguage('js', javascript);
    hljs.registerLanguage('typescript', typescript);
    hljs.registerLanguage('ts', typescript);
    hljs.registerLanguage('php', php);
    hljs.registerLanguage('python', python);
    hljs.registerLanguage('py', python);
    hljs.registerLanguage('bash', bash);
    hljs.registerLanguage('shell', bash);
    hljs.registerLanguage('sh', bash);
    hljs.registerLanguage('json', json);
    hljs.registerLanguage('sql', sql);
    hljs.registerLanguage('xml', xml);
    hljs.registerLanguage('html', xml);
    hljs.registerLanguage('css', css);
    hljs.registerLanguage('rust', rust);
    hljs.registerLanguage('go', go);
    hljs.registerLanguage('java', java);
    hljs.registerLanguage('c', c);
    hljs.registerLanguage('cpp', cpp);
    hljs.registerLanguage('yaml', yaml);
    hljs.registerLanguage('yml', yaml);
    hljs.registerLanguage('markdown', markdown);
    hljs.registerLanguage('md', markdown);
    hljs.registerLanguage('plaintext', plaintext);
    hljs.registerLanguage('text', plaintext);

    hljsReady = hljs;
    return hljs;
}

async function ensureMermaid() {
    if (mermaidReady) return mermaidReady;
    const mermaid = (await import('mermaid')).default;
    mermaid.initialize({
        startOnLoad: false,
        theme: 'dark',
        securityLevel: 'strict',
        fontFamily: 'Courier Prime, Courier New, monospace',
    });
    mermaidReady = mermaid;
    return mermaid;
}

async function highlightCodeBlocks(root) {
    const blocks = [...root.querySelectorAll('pre code')].filter((b) => !b.closest('pre.mermaid'));
    if (!blocks.length) return;
    const hljs = await loadHljs();
    blocks.forEach((block) => {
        if (block.dataset.highlighted === 'yes') return;
        try {
            hljs.highlightElement(block);
            block.dataset.highlighted = 'yes';
        } catch {
            // ignore
        }
    });
}

async function renderMermaid(root) {
    const nodes = root.querySelectorAll('pre.mermaid');
    if (!nodes.length) return;

    const mermaid = await ensureMermaid();
    try {
        await mermaid.run({ nodes: [...nodes] });
    } catch {
        // leave source visible
    }
}

async function renderMath(root) {
    if (!/\$|\\\(|\\\[/.test(root.textContent || '')) return;
    await import('katex/dist/katex.min.css');
    const renderMathInElement = (await import('katex/contrib/auto-render')).default;
    try {
        renderMathInElement(root, {
            delimiters: [
                { left: '$$', right: '$$', display: true },
                { left: '$', right: '$', display: false },
                { left: '\\(', right: '\\)', display: false },
                { left: '\\[', right: '\\]', display: true },
            ],
            throwOnError: false,
        });
    } catch {
        // ignore
    }
}

export async function enhanceMarkdownPreview(root) {
    if (!root) return;
    await highlightCodeBlocks(root);
    await renderMath(root);
    await renderMermaid(root);
}

export async function renderMarkdownToHtml(markdown) {
    const { marked } = await import('marked');
    marked.setOptions({ gfm: true, breaks: false });
    const raw = marked.parse(markdown || '', { async: false });
    let html = DOMPurify.sanitize(raw, {
        ADD_ATTR: ['target', 'rel', 'class', 'data-lang'],
    });

    html = html.replace(
        /<pre><code class="language-mermaid">([\s\S]*?)<\/code><\/pre>/gi,
        (_, code) => `<pre class="mermaid">${code}</pre>`,
    );

    return html;
}

export function initMarkdownPreviews() {
    document.querySelectorAll('.md-preview[data-md-enhance]').forEach((el) => {
        enhanceMarkdownPreview(el);
    });
}

export function initAdminMarkdownEditor() {
    const textarea = document.getElementById('blog-body');
    const preview = document.getElementById('blog-md-preview');
    const readTimeEl = document.getElementById('blog-read-time');
    if (!textarea || !preview) return;

    let timer = null;

    const update = async () => {
        const md = textarea.value;
        preview.innerHTML = await renderMarkdownToHtml(md);
        await enhanceMarkdownPreview(preview);

        if (readTimeEl) {
            const plain = md.replace(/```[\s\S]*?```/g, ' ').replace(/[`*_#>\[\]()!|-]/g, ' ');
            const words = plain.trim().split(/\s+/).filter(Boolean).length;
            const mins = Math.max(1, Math.ceil(words / 200));
            readTimeEl.textContent = `${mins} min read`;
        }
    };

    textarea.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(update, 250);
    });

    update();

    const uploadBtn = document.getElementById('blog-inline-image-btn');
    const uploadInput = document.getElementById('blog-inline-image-input');
    const uploadUrl = uploadBtn?.dataset.uploadUrl;

    uploadBtn?.addEventListener('click', () => uploadInput?.click());

    uploadInput?.addEventListener('change', async () => {
        const file = uploadInput.files?.[0];
        if (!file || !uploadUrl) return;

        const body = new FormData();
        body.append('image', file);
        body.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        const postId = uploadInput.dataset.postId;
        if (postId) {
            body.append('blog_post_id', postId);
        }

        try {
            const res = await fetch(uploadUrl, {
                method: 'POST',
                body,
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            if (!res.ok || !data.markdown) {
                throw new Error(data.message || 'Upload failed');
            }

            const start = textarea.selectionStart ?? textarea.value.length;
            const end = textarea.selectionEnd ?? start;
            const before = textarea.value.slice(0, start);
            const after = textarea.value.slice(end);
            const snippet = `\n${data.markdown}\n`;
            textarea.value = before + snippet + after;
            textarea.dispatchEvent(new Event('input'));
            textarea.focus();
        } catch (e) {
            alert(e.message || 'Image upload failed');
        } finally {
            uploadInput.value = '';
        }
    });
}

export function initBlogShare() {
    document.querySelectorAll('[data-blog-share]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const url = btn.dataset.shareUrl || window.location.href;
            const feedback = btn.parentElement?.querySelector('[data-blog-share-feedback]');
            try {
                await navigator.clipboard.writeText(url);
                if (feedback) {
                    feedback.textContent = 'Copied';
                    feedback.classList.remove('opacity-0');
                    setTimeout(() => {
                        feedback.classList.add('opacity-0');
                        feedback.textContent = '';
                    }, 2000);
                }
            } catch {
                if (feedback) {
                    feedback.textContent = 'Copy failed';
                    feedback.classList.remove('opacity-0');
                }
            }
        });
    });
}
