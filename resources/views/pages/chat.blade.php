@extends('layouts.app')

@section('title', 'AI Chat | JayXCoder')

@section('content')
<section class="section-pad relative overflow-hidden">
    <div class="hero-grid-bg pointer-events-none absolute inset-0 opacity-60"></div>
    <div class="site-container relative max-w-3xl">
        <div class="fade-in-view flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-3 flex items-center gap-2 font-mono text-xs uppercase tracking-[0.2em] text-uv-bright">
                    <span class="h-2 w-2 rounded-full {{ $isAvailable ? 'bg-success shadow-[0_0_12px_rgba(34,197,94,.8)]' : 'bg-danger' }}"></span>
                    Grounded portfolio intelligence
                </div>
                <h1 class="section-title">Ask the work itself.</h1>
                <p class="section-subtitle">Projects, skills, achievements, experience, and published LinkedIn insights—retrieved before every answer.</p>
            </div>
            <div class="hidden items-center gap-3 border border-border/80 bg-oled/70 px-3.5 py-2 font-mono text-[11px] uppercase tracking-[0.16em] text-text-dim sm:flex" title="Plans the search, retrieves portfolio evidence, then answers from sources">
                <span class="inline-flex items-center gap-1.5 text-uv-bright">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-uv-bright shadow-[0_0_10px_rgba(192,132,252,.7)]" aria-hidden="true"></span>
                    Agentic
                </span>
                <span class="text-border" aria-hidden="true">/</span>
                <span class="text-text-muted">RAG grounded</span>
            </div>
        </div>

        <div class="card-surface fade-in-view mt-8 flex h-[min(72dvh,640px)] flex-col overflow-hidden shadow-2xl shadow-uv/5">
            <div class="flex items-center justify-between border-b border-border bg-oled/60 px-4 py-3">
                <div class="flex gap-1.5" aria-hidden="true"><span class="h-2.5 w-2.5 rounded-full bg-danger/70"></span><span class="h-2.5 w-2.5 rounded-full bg-warning/70"></span><span class="h-2.5 w-2.5 rounded-full bg-success/70"></span></div>
                <p class="font-mono text-[11px] uppercase tracking-[0.18em] text-text-dim">jay://knowledge-agent</p>
                <button id="clear-chat" type="button" class="text-xs text-text-dim transition hover:text-text">Clear</button>
            </div>

            <div id="chat-messages" class="flex-1 space-y-4 overflow-y-auto p-4 sm:p-5" aria-live="polite">
                <div class="max-w-[92%] rounded-2xl rounded-tl-md border border-border bg-surface-muted px-4 py-3 text-sm leading-relaxed text-text-muted">
                    Ask what Jay has built, where a skill appears in his work, or what he has shared and achieved.
                </div>
            </div>

            <div id="agent-status" class="hidden border-t border-border/70 bg-oled/40 px-4 py-2 font-mono text-xs text-uv-bright" role="status">
                <span class="mr-2 inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-uv-bright"></span>
                <span data-stage>Planning retrieval…</span>
            </div>

            <form id="chat-form" class="flex gap-2 border-t border-border bg-surface/80 p-3 sm:p-4">
                @csrf
                <label for="chat-input" class="sr-only">Message</label>
                <input type="text" id="chat-input" class="input-field flex-1" placeholder="Ask about a project, skill, or achievement…" maxlength="2000" {{ $isAvailable ? '' : 'disabled' }} autocomplete="off">
                <button id="chat-submit" type="submit" class="btn-primary shrink-0 !px-4 sm:!px-5" {{ $isAvailable ? '' : 'disabled' }}>
                    <span class="hidden sm:inline">Send</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 14-7-4 14-3-6-7-1Zm7 1 7-8"/></svg>
                </button>
            </form>
            @unless($isAvailable)
                <p class="border-t border-border px-4 py-2 text-center text-xs text-warning">The local models are unavailable. Try again later.</p>
            @endunless
        </div>
    </div>
</section>

@push('scripts')
<script>
window.chatContext = [];
const stageCopy = {
    planning: 'Planning the knowledge search…',
    retrieving: 'Retrieving relevant evidence…',
    answering: 'Composing a grounded answer…',
};

document.getElementById('chat-form')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const input = document.getElementById('chat-input');
    const submit = document.getElementById('chat-submit');
    const box = document.getElementById('chat-messages');
    const status = document.getElementById('agent-status');
    const message = input.value.trim();
    if (!message || submit.disabled) return;

    input.value = '';
    input.disabled = true;
    submit.disabled = true;
    appendMessage(box, message, 'user');
    setStage(status, 'planning');

    try {
        const response = await fetch('{{ route('chat.stream') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/x-ndjson',
            },
            body: JSON.stringify({ message, context: window.chatContext || [] }),
        });
        if (!response.ok || !response.body) throw new Error('Chat request failed');

        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';
        while (true) {
            const { value, done } = await reader.read();
            buffer += decoder.decode(value || new Uint8Array(), { stream: !done });
            const lines = buffer.split('\n');
            buffer = lines.pop() || '';
            for (const line of lines) {
                if (!line.trim()) continue;
                const packet = JSON.parse(line);
                if (packet.event === 'status') {
                    setStage(status, packet.data.stage);
                } else if (packet.event === 'complete') {
                    window.chatContext = packet.data.context || [];
                    appendMessage(box, packet.data.message || 'No response', 'bot', packet.data);
                } else if (packet.event === 'error') {
                    appendMessage(box, packet.data.message || 'The assistant is unavailable.', 'bot');
                }
            }
            if (done) break;
        }
    } catch {
        appendMessage(box, 'Connection error. Please try again.', 'bot');
    } finally {
        status.classList.add('hidden');
        input.disabled = false;
        submit.disabled = false;
        input.focus();
    }
});

document.getElementById('clear-chat')?.addEventListener('click', () => {
    window.chatContext = [];
    document.getElementById('chat-messages').innerHTML = '<div class="max-w-[92%] rounded-2xl rounded-tl-md border border-border bg-surface-muted px-4 py-3 text-sm text-text-muted">Context cleared. What would you like to know?</div>';
});

function setStage(element, stage) {
    element.classList.remove('hidden');
    element.querySelector('[data-stage]').textContent = stageCopy[stage] || 'Working…';
}

function appendMessage(box, text, role, options = {}) {
    const wrapper = document.createElement('div');
    const isUser = role === 'user';
    wrapper.className = 'max-w-[92%] rounded-2xl px-4 py-3 text-sm ' +
        (isUser ? 'ml-auto rounded-tr-md bg-uv/20 text-text' : 'rounded-tl-md border border-border bg-surface-muted text-text');

    const body = document.createElement('div');
    body.className = 'chat-message-body whitespace-pre-wrap leading-relaxed';
    if (!isUser && options.message_html) body.innerHTML = options.message_html;
    else if (!isUser) body.innerHTML = formatChatMarkdown(text);
    else body.textContent = text;
    wrapper.appendChild(body);

    if (!isUser && options.sources?.length) {
        const sourceBlock = document.createElement('div');
        sourceBlock.className = 'mt-4 border-t border-border pt-3';
        const label = document.createElement('p');
        label.className = 'mb-2 font-mono text-[10px] uppercase tracking-[0.18em] text-text-dim';
        label.textContent = 'Retrieved sources';
        sourceBlock.appendChild(label);
        const list = document.createElement('div');
        list.className = 'flex flex-wrap gap-2';
        options.sources.forEach((source) => {
            const link = document.createElement('a');
            link.href = source.url;
            link.target = source.type === 'linkedin_post' ? '_blank' : '_self';
            if (link.target === '_blank') link.rel = 'noopener';
            link.className = 'group rounded-xl border border-border bg-oled/50 px-3 py-2 transition hover:border-uv/50';
            link.innerHTML = '<span class="block text-[10px] uppercase tracking-wider text-uv-bright"></span><span class="mt-0.5 block max-w-52 truncate text-xs text-text"></span>';
            link.children[0].textContent = source.label;
            link.children[1].textContent = source.title;
            list.appendChild(link);
        });
        sourceBlock.appendChild(list);
        wrapper.appendChild(sourceBlock);
    }

    box.appendChild(wrapper);
    box.scrollTop = box.scrollHeight;
}

function escapeHtml(text) {
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function formatChatMarkdown(text) {
    let safe = escapeHtml(text);
    safe = safe.replace(/\*\*(.+?)\*\*/gs, '<strong class="font-semibold text-text">$1</strong>');
    safe = safe.replace(/(?<!\*)\*([^*\n]+)\*(?!\*)/g, '<em class="text-text-muted">$1</em>');
    safe = safe.replace(/`([^`\n]+)`/g, '<code class="rounded bg-oled/60 px-1 py-0.5 text-uv-bright text-xs">$1</code>');
    return safe.replace(/\n/g, '<br>');
}
</script>
@endpush
@endsection
