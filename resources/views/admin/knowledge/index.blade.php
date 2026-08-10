@extends('layouts.admin')

@section('title', 'Knowledge Engine')
@section('page_heading', 'Knowledge Engine')

@section('content')
<x-admin.page-header
    title="RAG control room"
    description="Index portfolio facts, connect LinkedIn, and verify the models behind the public assistant."
/>

<div class="relative overflow-hidden rounded-2xl border border-uv/30 bg-surface-elevated p-5 sm:p-6">
    <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-uv/10 blur-3xl"></div>
    <div class="relative grid gap-5 lg:grid-cols-[1.35fr_1fr] lg:items-center">
        <div>
            <div class="mb-3 flex items-center gap-2 font-mono text-xs uppercase tracking-[0.2em] text-uv-bright">
                <span class="h-2 w-2 rounded-full {{ $health['available'] && $health['chat_model'] && $health['embedding_model'] ? 'bg-success' : 'bg-danger' }}"></span>
                Local intelligence pipeline
            </div>
            <h2 class="font-display text-2xl font-bold text-text sm:text-3xl">Grounded in your work, not guesses.</h2>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-text-muted">
                Qwen plans each search, the embedding model retrieves evidence, and the answer is composed only from indexed sources.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl border border-border bg-oled/60 p-4">
                <p class="text-xs uppercase tracking-wider text-text-dim">Documents</p>
                <p class="mt-1 font-display text-3xl font-bold text-text">{{ $stats['documents'] }}</p>
            </div>
            <div class="rounded-xl border border-border bg-oled/60 p-4">
                <p class="text-xs uppercase tracking-wider text-text-dim">Vectors</p>
                <p class="mt-1 font-display text-3xl font-bold text-uv-bright">{{ $stats['chunks'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-2">
    <section class="card-surface p-5 sm:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-uv-bright">01 / Models</p>
                <h3 class="mt-2 font-display text-xl font-semibold">Ollama health</h3>
            </div>
            <span class="badge-uv">{{ $health['available'] ? 'Reachable' : 'Offline' }}</span>
        </div>
        <div class="mt-5 space-y-3">
            <div class="flex items-center justify-between gap-4 rounded-xl border border-border bg-oled/50 p-4">
                <div>
                    <p class="text-xs text-text-dim">Think + respond</p>
                    <p class="font-mono text-sm text-text">{{ $chatModel }}</p>
                </div>
                <span class="{{ $health['chat_model'] ? 'text-success' : 'text-danger' }}">{{ $health['chat_model'] ? 'ready' : 'missing' }}</span>
            </div>
            <div class="flex items-center justify-between gap-4 rounded-xl border border-border bg-oled/50 p-4">
                <div>
                    <p class="text-xs text-text-dim">Dense retrieval</p>
                    <p class="font-mono text-sm text-text">{{ $embeddingModel }}</p>
                </div>
                <span class="{{ $health['embedding_model'] ? 'text-success' : 'text-danger' }}">{{ $health['embedding_model'] ? 'ready' : 'missing' }}</span>
            </div>
        </div>
        <form action="{{ route('admin.knowledge.reindex') }}" method="POST" class="mt-5 flex flex-col gap-3 sm:flex-row">
            @csrf
            <label for="source" class="sr-only">Knowledge source</label>
            <select id="source" name="source" class="input-field">
                <option value="all">All portfolio sources</option>
                <option value="profile">Profile</option>
                <option value="skills">Skills</option>
                <option value="portfolio">Projects</option>
                <option value="achievement">Achievements</option>
                <option value="experience">Experience</option>
                <option value="linkedin_post">LinkedIn posts</option>
            </select>
            <button class="btn-primary shrink-0" type="submit">Queue reindex</button>
        </form>
        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-text-dim">
            <span>{{ $stats['failed_documents'] }} indexing errors</span>
            <span>Last sync: {{ $stats['last_sync']?->finished_at?->diffForHumans() ?? 'never' }}</span>
        </div>
        @if($failedDocuments->isNotEmpty())
            <div class="mt-4 max-h-48 space-y-2 overflow-y-auto rounded-xl border border-danger/30 bg-danger/5 p-3">
                @foreach($failedDocuments as $failed)
                    <div class="text-xs">
                        <p class="font-medium text-text">{{ $failed->title }} <span class="text-text-dim">({{ $failed->source_type }})</span></p>
                        <p class="mt-0.5 break-words text-danger">{{ $failed->last_error }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="card-surface p-5 sm:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-uv-bright">02 / LinkedIn</p>
                <h3 class="mt-2 font-display text-xl font-semibold">Post knowledge</h3>
            </div>
            <span class="badge-uv">{{ $connection?->status ?? 'not connected' }}</span>
        </div>

        @if($connection)
            <div class="mt-5 rounded-xl border border-border bg-oled/50 p-4 text-sm">
                <p class="text-text">Official API connected</p>
                <p class="mt-1 text-xs text-text-dim">Last sync: {{ $connection->last_synced_at?->diffForHumans() ?? 'pending' }}</p>
                @if($connection->last_error)
                    <p class="mt-2 text-xs text-danger">{{ $connection->last_error }}</p>
                @endif
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <form action="{{ route('admin.knowledge.linkedin.sync') }}" method="POST">@csrf<button class="btn-primary" type="submit">Sync now</button></form>
                <form action="{{ route('admin.knowledge.linkedin.disconnect') }}" method="POST">@csrf @method('DELETE')<button class="btn-secondary" type="submit">Disconnect</button></form>
            </div>
        @else
            <p class="mt-5 text-sm leading-relaxed text-text-muted">
                Connect with approved <code class="text-uv-bright">r_member_social</code> access. If approval is unavailable, import your LinkedIn data export below.
            </p>
            @if($linkedinConfigured)
                <a href="{{ route('admin.knowledge.linkedin.connect') }}" class="btn-primary mt-4">Connect LinkedIn</a>
            @else
                <p class="mt-4 rounded-xl border border-warning/30 bg-warning/10 p-3 text-xs text-warning">Add LinkedIn client credentials to enable OAuth.</p>
            @endif
        @endif

        <div class="my-5 flex items-center gap-3 text-xs text-text-dim"><span class="h-px flex-1 bg-border"></span>export fallback<span class="h-px flex-1 bg-border"></span></div>
        <form action="{{ route('admin.knowledge.linkedin.import') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <label for="linkedin_export" class="label-field">Shares.csv or LinkedIn export ZIP</label>
            <input id="linkedin_export" name="linkedin_export" type="file" accept=".csv,.zip,text/csv,application/zip" class="input-field !py-2" required>
            <button class="btn-secondary w-full sm:w-auto" type="submit">Import posts</button>
        </form>
    </section>
</div>
@endsection
