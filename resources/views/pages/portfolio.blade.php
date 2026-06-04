@extends('layouts.app')

@section('title', 'Portfolio | Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view">Portfolio</h1>
        <p class="section-subtitle fade-in-view">AI/ML, cybersecurity, full-stack, hardware, and infrastructure. Built as a self-taught engineer.</p>

        <div class="mt-10 flex flex-wrap justify-center gap-2 fade-in-view" role="tablist" aria-label="Filter projects">
            @foreach(['all' => 'All', 'AI/ML' => 'AI/ML', 'Hardware/IoT' => 'IoT', 'Web Development' => 'Web', 'Cybersecurity' => 'Security', 'Mobile Development' => 'Mobile', 'Infrastructure' => 'Infra'] as $key => $label)
            <button type="button" data-filter="{{ $key === 'all' ? 'all' : $key }}" class="filter-chip {{ $key === 'all' ? 'filter-chip-active' : '' }}">{{ $label }}</button>
            @endforeach
        </div>

        <div id="portfolio-grid" class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($portfolioItems as $item)
                <x-portfolio-card :item="$item" />
            @empty
            <p class="col-span-full text-center text-text-muted">No portfolio items published yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
