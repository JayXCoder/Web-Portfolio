@extends('layouts.admin')

@section('title', 'Comment')
@section('page_heading', 'Comment')

@section('content')
<x-admin.page-header title="Comment detail" description="From {{ $comment->author_name }}">
    <x-slot:actions>
        <a href="{{ route('admin.blog-comments') }}" class="btn-secondary">Back</a>
        <form method="POST" action="{{ route('admin.blog-comments.delete', $comment) }}" onsubmit="return confirm('Delete this comment?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-secondary border-danger/50 text-red-300">Delete</button>
        </form>
    </x-slot:actions>
</x-admin.page-header>

<div class="card-surface space-y-4 p-6">
    <div>
        <p class="text-xs uppercase tracking-wide text-text-dim">Post</p>
        @if($comment->post)
            <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank" class="text-uv-bright hover:text-uv-glow">
                {{ $comment->post->title }}
            </a>
        @else
            <p class="text-text-muted">Post deleted</p>
        @endif
    </div>
    <div>
        <p class="text-xs uppercase tracking-wide text-text-dim">Author</p>
        <p class="text-text">{{ $comment->author_name }}</p>
    </div>
    <div>
        <p class="text-xs uppercase tracking-wide text-text-dim">Email</p>
        <p class="text-text-muted">{{ $comment->author_email ?: '—' }}</p>
    </div>
    <div>
        <p class="text-xs uppercase tracking-wide text-text-dim">IP</p>
        <p class="text-text-muted">{{ $comment->ip_address ?: '—' }}</p>
    </div>
    <div>
        <p class="text-xs uppercase tracking-wide text-text-dim">Posted</p>
        <p class="text-text-muted">{{ $comment->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <div>
        <p class="text-xs uppercase tracking-wide text-text-dim">Body</p>
        <p class="mt-2 whitespace-pre-wrap text-text leading-relaxed">{{ $comment->body }}</p>
    </div>
</div>
@endsection
