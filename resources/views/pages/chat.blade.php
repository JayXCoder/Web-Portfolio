@extends('layouts.app')

@section('title', 'AI Chat | JayXCoder')

@section('content')
<section class="section-pad">
    <div class="site-container max-w-2xl">
        <h1 class="section-title fade-in-view">AI assistant</h1>
        <p class="section-subtitle fade-in-view">
            Ask about my projects, skills, and experience.
        </p>

        <div class="card-surface mt-8 flex h-[min(70dvh,560px)] flex-col fade-in-view">
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3" aria-live="polite">
                <div class="rounded-xl bg-surface-muted px-4 py-3 text-sm text-text-muted">
                    Ask about a portfolio project, technology I use, or my work experience.
                </div>
            </div>
            <form id="chat-form" class="border-t border-border p-4 flex gap-2">
                @csrf
                <label for="chat-input" class="sr-only">Message</label>
                <input type="text" id="chat-input" class="input-field flex-1" placeholder="Type a message…" maxlength="2000" {{ $isAvailable ? '' : 'disabled' }} autocomplete="off">
                <button type="submit" class="btn-primary shrink-0" {{ $isAvailable ? '' : 'disabled' }}>Send</button>
            </form>
            @unless($isAvailable)
            <p class="border-t border-border px-4 py-2 text-center text-xs text-warning">Assistant is temporarily unavailable. Try again later.</p>
            @endunless
        </div>
    </div>
</section>

@push('scripts')
<script>
window.chatContext = [];
document.getElementById('chat-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('chat-input');
    const box = document.getElementById('chat-messages');
    const msg = input.value.trim();
    if (!msg) return;
    input.value = '';
    appendMsg(box, msg, 'user');
    appendMsg(box, 'Thinking…', 'bot', { loading: true });
    try {
        const res = await fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',
            },
            body: JSON.stringify({ message: msg, context: window.chatContext || [] }),
        });
        const data = await res.json();
        box.querySelector('[data-loading]')?.remove();
        if (data.context) window.chatContext = data.context;
        appendMsg(box, data.message || data.error || 'No response', 'bot', {
            related_projects: data.related_projects || [],
        });
    } catch {
        box.querySelector('[data-loading]')?.remove();
        appendMsg(box, 'Connection error.', 'bot');
    }
});

function appendMsg(box, text, role, options = {}) {
    const el = document.createElement('div');
    const isUser = role === 'user';
    el.className =
        'rounded-xl px-4 py-3 text-sm max-w-[90%] ' +
        (isUser ? 'ml-auto bg-uv/20 text-text' : 'bg-surface-muted text-text');

    if (options.loading) {
        el.dataset.loading = '1';
        el.classList.add('text-text-muted');
    }

    const p = document.createElement('p');
    p.className = 'whitespace-pre-wrap';
    p.textContent = text;
    el.appendChild(p);

    if (!isUser && options.related_projects?.length) {
        const actions = document.createElement('div');
        actions.className = 'mt-3 flex flex-wrap gap-2';
        options.related_projects.forEach((project) => {
            const a = document.createElement('a');
            a.href = project.url;
            a.className = 'btn-secondary text-xs !min-h-9 !px-3 !py-1.5';
            a.textContent = 'View project: ' + project.title;
            actions.appendChild(a);
        });
        el.appendChild(actions);
    }

    box.appendChild(el);
    box.scrollTop = box.scrollHeight;
}
</script>
@endpush
@endsection
