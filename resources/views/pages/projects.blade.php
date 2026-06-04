@extends('layouts.app')

@section('title', 'Projects | Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <div class="text-center">
            <h1 class="section-title fade-in-view">Projects</h1>
            <p class="section-subtitle mx-auto fade-in-view">Highlights from my portfolio: case studies with stack, challenges, and outcomes.</p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($portfolioItems as $item)
                <x-portfolio-card :item="$item" />
            @empty
                <p class="col-span-full text-center text-text-muted">No published projects yet. Check back soon.</p>
            @endforelse
        </div>

        <div class="mt-12 text-center fade-in-view">
            <a href="{{ route('portfolio') }}" class="btn-primary inline-flex">Browse full portfolio</a>
        </div>
    </div>
</section>
@endsection
