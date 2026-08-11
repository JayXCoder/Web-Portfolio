@extends('layouts.admin')

@section('title', 'Blog Comments')
@section('page_heading', 'Blog Comments')

@section('content')
<x-admin.page-header title="Blog comments" description="Monitor and delete comments. Comments appear publicly immediately." />

<form method="GET" action="{{ route('admin.blog-comments') }}" class="mb-6 flex flex-wrap items-end gap-3">
    <div>
        <label for="post" class="label-field">Filter by post</label>
        <select id="post" name="post" class="input-field mt-1 min-w-[14rem]">
            <option value="">All posts</option>
            @foreach($posts as $p)
                <option value="{{ $p->id }}" @selected($postId === $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-secondary">Filter</button>
</form>

@if($comments->isEmpty())
    <div class="card-surface p-8 text-center text-text-muted">No comments.</div>
@else
<form id="blog-comments-bulk-form" method="POST" action="{{ route('admin.blog-comments.bulk-delete') }}" class="space-y-4">
    @csrf
    @method('DELETE')

    <div class="flex flex-wrap items-center justify-between gap-3">
        <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-text-muted">
            <input id="blog-comments-select-all" type="checkbox" class="rounded border-border text-uv focus:ring-uv">
            Select all
        </label>
        <button
            id="blog-comments-bulk-delete"
            type="submit"
            class="btn-secondary border-danger/50 text-red-300 hover:border-danger disabled:cursor-not-allowed disabled:opacity-40"
            disabled
            onclick="return confirm('Delete selected comments?')"
        >
            Delete selected
        </button>
    </div>

    <div class="card-surface divide-y divide-border">
        @foreach($comments as $comment)
            <div class="flex items-start gap-3 p-4 transition hover:bg-surface-muted/40 sm:items-center">
                <input
                    type="checkbox"
                    name="ids[]"
                    value="{{ $comment->id }}"
                    class="blog-comment-checkbox mt-1 rounded border-border text-uv focus:ring-uv sm:mt-0"
                    aria-label="Select comment by {{ $comment->author_name }}"
                >
                <a href="{{ route('admin.blog-comments.show', $comment) }}" class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium text-text">
                            {{ $comment->author_name }}
                            <span class="font-normal text-text-dim">· {{ $comment->post?->title ?? 'Deleted post' }}</span>
                        </p>
                        <p class="line-clamp-1 text-sm text-text-muted">{{ Str::limit($comment->body, 100) }}</p>
                    </div>
                    <span class="mt-1 inline-block text-xs text-text-dim sm:mt-0">{{ $comment->created_at->diffForHumans() }}</span>
                </a>
            </div>
        @endforeach
    </div>
</form>

@push('scripts')
<script>
(() => {
    const selectAll = document.getElementById('blog-comments-select-all');
    const boxes = document.querySelectorAll('.blog-comment-checkbox');
    const btn = document.getElementById('blog-comments-bulk-delete');
    const sync = () => {
        const n = [...boxes].filter((b) => b.checked).length;
        btn.disabled = n === 0;
        if (selectAll) selectAll.checked = n > 0 && n === boxes.length;
    };
    selectAll?.addEventListener('change', () => {
        boxes.forEach((b) => { b.checked = selectAll.checked; });
        sync();
    });
    boxes.forEach((b) => b.addEventListener('change', sync));
    sync();
})();
</script>
@endpush
@endif
@endsection
