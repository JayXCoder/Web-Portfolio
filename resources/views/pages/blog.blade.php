@extends('layouts.app')

@section('title', 'Blog | Jawahar Ganesh @ Jay')
@section('description', 'Technical notes, build logs, and essays from Jawahar Ganesh @ Jay.')
@section('og_title', 'Blog | JayXCoder')
@section('og_type', 'website')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view blog-type">Blog</h1>
        <p class="section-subtitle fade-in-view blog-type">Notes, build logs, and long-form writing.</p>

        <div class="mt-12 mx-auto max-w-3xl space-y-0">
            @forelse($posts as $post)
                <article class="fade-in-view border-t border-border py-8 first:border-t-0">
                    <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                        <div class="flex flex-col gap-4 sm:flex-row sm:gap-6">
                            @if($post->coverImageUrl())
                                <img
                                    src="{{ $post->coverImageUrl() }}"
                                    alt=""
                                    class="h-28 w-full shrink-0 object-cover sm:h-24 sm:w-36"
                                    loading="lazy"
                                    width="144"
                                    height="96"
                                >
                            @endif
                            <div class="min-w-0 flex-1">
                                <h2 class="blog-type text-xl font-bold text-text transition group-hover:text-uv-bright sm:text-2xl">
                                    {{ $post->title }}
                                </h2>
                                <p class="blog-meta mt-2">
                                    <span>{{ $post->author_name }}</span>
                                    <span aria-hidden="true">·</span>
                                    <span>{{ $post->reading_time_minutes }} min read</span>
                                    <span aria-hidden="true">·</span>
                                    <span>{{ number_format($post->views_count) }} views</span>
                                    @if($post->published_at)
                                        <span aria-hidden="true">·</span>
                                        <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M j, Y') }}</time>
                                    @endif
                                </p>
                                @if($post->excerpt)
                                    <p class="blog-type mt-3 text-text-muted leading-relaxed">{{ $post->excerpt }}</p>
                                @endif
                                @if(!empty($post->tags))
                                    <p class="mt-3 flex flex-wrap gap-2">
                                        @foreach($post->tags as $tag)
                                            <span class="badge-uv">{{ $tag }}</span>
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                        </div>
                    </a>
                </article>
            @empty
                <p class="blog-type text-center text-text-muted py-16">No posts published yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
