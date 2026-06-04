@extends('layouts.admin')

@section('title', 'Create Portfolio')
@section('page_heading', 'Create portfolio')

@section('content')
<x-admin.page-header title="New portfolio" description="Generate from markdown with Ollama, or fill the form manually." />

<div class="mb-6 flex gap-2 border-b border-border" role="tablist">
    <button type="button" data-tab="ai" class="tab-btn border-b-2 border-uv px-4 py-2 text-sm font-medium text-uv-bright">AI from Markdown</button>
    <button type="button" data-tab="manual" class="tab-btn border-b-2 border-transparent px-4 py-2 text-sm font-medium text-text-muted hover:text-text">Manual form</button>
</div>

{{-- AI tab --}}
<div id="tab-ai" class="tab-panel">
    <div class="grid gap-6 lg:grid-cols-2">
        <form id="portfolio-ai-form" class="card-surface p-6 space-y-4"
              data-generate-url="{{ route('admin.portfolios.ai.generate') }}"
              data-save-url="{{ route('admin.portfolios.ai.save') }}"
              data-index-url="{{ route('admin.portfolios') }}">
            @csrf
            <div id="ollama-status" class="rounded-xl border border-border bg-surface-muted px-4 py-3 text-sm text-text-muted">
                Checking Ollama…
            </div>
            <div>
                <label for="markdown_files" class="label-field">Upload markdown files (.md)</label>
                <input type="file" id="markdown_files" name="markdown_files[]" accept=".md,.txt" multiple class="input-field file:mr-4 file:rounded-lg file:border-0 file:bg-uv file:px-4 file:py-2 file:text-sm file:text-white">
                <p class="mt-1 text-xs text-text-dim">Select multiple .md files to merge into one portfolio entry, or paste combined notes below.</p>
            </div>
            <div>
                <label for="markdown_paste" class="label-field">Or paste markdown</label>
                <textarea id="markdown_paste" name="markdown_paste" rows="10" class="input-field font-mono text-xs" placeholder="# Project name&#10;&#10;## Overview&#10;..."></textarea>
            </div>
            <button type="button" id="ai-generate-btn" class="btn-primary w-full">Generate with Ollama</button>
            <div id="ai-status" class="hidden" role="status"></div>
        </form>

        <div class="card-surface p-6">
            <h3 class="font-display font-semibold text-text">Preview</h3>
            <p class="mt-1 text-sm text-text-muted">Review the AI draft before saving. You can edit fields after save.</p>
            <div id="ai-preview" class="mt-4 hidden rounded-xl border border-border bg-oled p-4"></div>
            <button type="button" id="ai-save-btn" class="btn-primary mt-4 hidden w-full">Save portfolio</button>
        </div>
    </div>
</div>

{{-- Manual tab --}}
<div id="tab-manual" class="tab-panel hidden">
    @include('admin.portfolios._form', ['portfolio' => null, 'action' => route('admin.portfolios.store'), 'method' => 'POST'])
</div>

@push('scripts')
<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.toggle('border-uv', b.dataset.tab === tab);
            b.classList.toggle('text-uv-bright', b.dataset.tab === tab);
            b.classList.toggle('border-transparent', b.dataset.tab !== tab);
            b.classList.toggle('text-text-muted', b.dataset.tab !== tab);
        });
        document.getElementById('tab-ai').classList.toggle('hidden', tab !== 'ai');
        document.getElementById('tab-manual').classList.toggle('hidden', tab !== 'manual');
    });
});
fetch('{{ route('admin.portfolios.ai.status') }}', {
    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
})
    .then(async (r) => {
        const text = await r.text();
        try {
            return JSON.parse(text);
        } catch {
            return { available: false, api_url: 'unknown', model: '' };
        }
    })
    .then(d => {
        const el = document.getElementById('ollama-status');
        if (d.available) {
            el.className = 'rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-green-300';
            el.textContent = `Ollama online: ${d.model} @ ${d.api_url}`;
        } else {
            el.className = 'rounded-xl border border-warning/30 bg-warning/10 px-4 py-3 text-sm text-yellow-200';
            el.textContent = `Ollama offline. Check OLLAMA_HOST in .env (${d.api_url})`;
        }
    });
</script>
@endpush
@endsection
