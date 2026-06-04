@extends('layouts.app')

@section('title', 'Portfolio — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view">Portfolio</h1>
        <p class="section-subtitle fade-in-view">AI/ML, cybersecurity, full-stack, hardware, and infrastructure — built as a self-taught engineer.</p>

        <div class="mt-10 flex flex-wrap justify-center gap-2 fade-in-view" role="tablist" aria-label="Filter projects">
            @foreach(['all' => 'All', 'AI/ML' => 'AI/ML', 'Hardware/IoT' => 'IoT', 'Web Development' => 'Web', 'Cybersecurity' => 'Security', 'Mobile Development' => 'Mobile', 'Infrastructure' => 'Infra'] as $key => $label)
            <button type="button" data-filter="{{ $key === 'all' ? 'all' : $key }}" class="filter-chip {{ $key === 'all' ? 'filter-chip-active' : '' }}">{{ $label }}</button>
            @endforeach
        </div>

        <div id="portfolio-grid" class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($portfolioItems as $item)
            <article class="card-surface-hover fade-in-view overflow-hidden flex flex-col" data-category="{{ $item->category }}">
                <div class="aspect-video bg-surface-muted">
                    @if($item->main_image)
                        @php $src = filter_var($item->main_image, FILTER_VALIDATE_URL) ? $item->main_image : route('portfolio.image', basename($item->main_image)); @endphp
                        <img src="{{ $src }}" alt="{{ $item->title }}" class="h-full w-full object-cover" loading="lazy" width="640" height="360">
                    @else
                        <div class="flex h-full items-center justify-center text-text-dim text-sm">No image</div>
                    @endif
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <span class="badge-uv mb-2 w-fit">{{ $item->category }}</span>
                    <h2 class="font-display text-lg font-semibold text-text">{{ $item->title }}</h2>
                    <p class="mt-2 flex-1 text-sm text-text-muted line-clamp-3">{{ $item->short_description }}</p>
                    <a href="{{ route('portfolio.item', $item->slug) }}" class="btn-primary mt-4 w-full text-center">View project</a>
                </div>
            </article>
            @empty
            <p class="col-span-full text-center text-text-muted">No portfolio items published yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
