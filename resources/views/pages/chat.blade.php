@extends('layouts.app')

@section('title', 'AI Chat | JayXCoder')

@section('content')
<section class="section-pad">
    <div class="site-container max-w-2xl">
        <h1 class="section-title fade-in-view">AI assistant</h1>
        <p class="section-subtitle fade-in-view">
            Ask about my projects, skills, and experience only. Powered by Ollama.
            @if($isAvailable)
                <span class="text-success">· online</span>
            @else
                <span class="text-warning">· offline</span>
            @endif
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
    appendMsg(box, 'Thinking…', 'bot', true);
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
        appendMsg(box, data.message || data.error || 'No response', 'bot');
    } catch {
        box.querySelector('[data-loading]')?.remove();
        appendMsg(box, 'Connection error.', 'bot');
    }
});
function appendMsg(box, text, role, loading) {
    const el = document.createElement('div');
    el.className = 'rounded-xl px-4 py-3 text-sm max-w-[90%] ' + (role === 'user' ? 'ml-auto bg-uv/20 text-text' : 'bg-surface-muted text-text-muted');
    if (loading) el.dataset.loading = '1';
    el.textContent = text;
    box.appendChild(el);
    box.scrollTop = box.scrollHeight;
}
</script>
@endpush
@endsection
