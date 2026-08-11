@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page_heading', 'Blog')

@section('content')
<x-admin.page-header title="Blog posts" description="Write Markdown posts with live preview. Publishing queues RAG reindex.">
    <x-slot:actions>
        <a href="{{ route('admin.blog-posts.create') }}" class="btn-primary">+ New post</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="card-surface overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="border-b border-border bg-surface-muted/50 text-text-dim">
                <tr>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="px-4 py-3 font-medium">Author</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Views</th>
                    <th class="px-4 py-3 font-medium">Comments</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($posts as $post)
                <tr class="transition hover:bg-surface-muted/30">
                    <td class="px-4 py-3">
                        <p class="font-medium text-text">{{ $post->title }}</p>
                        <p class="text-xs text-text-dim">{{ $post->slug }}</p>
                    </td>
                    <td class="px-4 py-3 text-text-muted">{{ $post->author_name }}</td>
                    <td class="px-4 py-3">
                        @if($post->is_published)
                            <span class="text-xs text-success">Published</span>
                        @else
                            <span class="text-xs text-warning">Draft</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-text-muted">{{ number_format($post->views_count) }}</td>
                    <td class="px-4 py-3 text-text-muted">{{ $post->comments_count }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        @if($post->is_published)
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn-ghost inline-flex min-h-9 py-1 text-xs">View</a>
                        @endif
                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn-secondary inline-flex min-h-9 py-1 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.blog-posts.delete', $post) }}" class="inline" onsubmit="return confirm('Delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-ghost inline-flex min-h-9 py-1 text-xs text-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-text-muted">
                        No posts yet. <a href="{{ route('admin.blog-posts.create') }}" class="text-uv-bright">Create one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
