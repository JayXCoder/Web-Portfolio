@extends('layouts.app')

@php
    $shareUrl = route('blog.show', $post->slug);
    $ogImage = $post->absoluteCoverImageUrl();
@endphp

@section('title', $post->title.' | JayXCoder Blog')
@section('description', $post->seo_description)
@section('og_title', $post->title)
@section('og_type', 'article')
@section('og_url', $shareUrl)
@section('canonical', $shareUrl)
@section('article_author', $post->author_name)
@section('article_published_time', optional($post->published_at)?->toIso8601String() ?? '')
@section('og_image', $ogImage ?? '')
@section('twitter_image', $ogImage ?? '')

@section('structured_data')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->seo_description,
    'author' => [
        '@type' => 'Person',
        'name' => $post->author_name,
    ],
    'datePublished' => optional($post->published_at)?->toIso8601String(),
    'dateModified' => optional($post->updated_at)?->toIso8601String(),
    'image' => $ogImage ? [$ogImage] : null,
    'mainEntityOfPage' => $shareUrl,
    'url' => $shareUrl,
]), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<article class="section-pad">
    <div class="site-container">
        <div class="mx-auto max-w-3xl">
            <p class="mb-6">
                <a href="{{ route('blog') }}" class="blog-type text-sm text-uv-bright hover:text-uv-glow">← Blog</a>
            </p>

            <h1 class="blog-type text-3xl font-bold leading-tight text-text sm:text-4xl fade-in-view">{{ $post->title }}</h1>

            <div class="blog-meta mt-4 fade-in-view">
                <span>{{ $post->author_name }}</span>
                <span aria-hidden="true">·</span>
                <span>{{ $post->reading_time_minutes }} min read</span>
                <span aria-hidden="true">·</span>
                <span>{{ number_format($post->views_count) }} views</span>
                @if($post->published_at)
                    <span aria-hidden="true">·</span>
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M j, Y') }}</time>
                @endif
                <span class="inline-flex items-center gap-2 pl-1">
                    <button
                        type="button"
                        class="blog-share-btn"
                        data-blog-share
                        data-share-url="{{ $shareUrl }}"
                        aria-label="Copy link to share"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/>
                        </svg>
                        Share
                    </button>
                    <span class="text-xs text-success opacity-0 transition" data-blog-share-feedback aria-live="polite"></span>
                </span>
            </div>

            @if(!empty($post->tags))
                <p class="mt-4 flex flex-wrap gap-2 fade-in-view">
                    @foreach($post->tags as $tag)
                        <span class="badge-uv">{{ $tag }}</span>
                    @endforeach
                </p>
            @endif

            @if($post->coverImageUrl())
                <img
                    src="{{ $post->coverImageUrl() }}"
                    alt=""
                    class="mt-8 w-full object-cover fade-in-view"
                    width="1200"
                    height="630"
                >
            @endif

            <div class="md-preview mt-10" data-md-enhance>
                {!! $html !!}
            </div>

            <section id="comments" class="mt-16 border-t border-border pt-10">
                <h2 class="blog-type text-2xl font-bold text-text">Comments</h2>

                @if(session('success'))
                    <p class="mt-4 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-green-300" role="status">{{ session('success') }}</p>
                @endif

                <div class="mt-8">
                    @forelse($comments as $comment)
                        <div class="blog-comment">
                            <p class="text-sm text-text-dim">
                                <span class="font-bold text-text">{{ $comment->author_name }}</span>
                                <span aria-hidden="true"> · </span>
                                <time datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->format('M j, Y') }}</time>
                            </p>
                            <p class="mt-2 whitespace-pre-wrap text-text-muted leading-relaxed">{{ $comment->body }}</p>
                        </div>
                    @empty
                        <p class="blog-type py-6 text-text-dim">No comments yet. Be the first.</p>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('blog.comments.store', $post->slug) }}" class="mt-10 space-y-4">
                    @csrf
                    <h3 class="blog-type text-lg font-bold text-text">Leave a comment</h3>
                    <div>
                        <label for="author_name" class="label-field">Name</label>
                        <input id="author_name" name="author_name" type="text" value="{{ old('author_name') }}" required maxlength="120" class="input-field mt-1">
                        @error('author_name')<p class="mt-1 text-sm text-danger" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="author_email" class="label-field">Email <span class="text-text-dim font-normal">(optional, not shown publicly)</span></label>
                        <input id="author_email" name="author_email" type="email" value="{{ old('author_email') }}" maxlength="255" class="input-field mt-1" autocomplete="email">
                        @error('author_email')<p class="mt-1 text-sm text-danger" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="body" class="label-field">Comment</label>
                        <textarea id="body" name="body" rows="5" required maxlength="5000" class="input-field mt-1">{{ old('body') }}</textarea>
                        @error('body')<p class="mt-1 text-sm text-danger" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-primary">Post comment</button>
                </form>
            </section>
        </div>
    </div>
</article>
@endsection
