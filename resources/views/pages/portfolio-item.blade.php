@extends('layouts.app')

@section('title', $portfolio->title . ' | Portfolio')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <nav class="mb-6 text-sm text-text-muted fade-in-view" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="text-uv-bright hover:underline">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('portfolio') }}" class="text-uv-bright hover:underline">Portfolio</a>
            <span class="mx-2">/</span>
            <span class="text-text-dim">{{ $portfolio->title }}</span>
        </nav>

        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2 fade-in-view">
                <span class="badge-uv">{{ $portfolio->category }}</span>
                <h1 class="mt-3 font-display text-3xl font-bold text-text sm:text-4xl">{{ $portfolio->title }}</h1>
                <p class="mt-4 whitespace-pre-line text-text-muted leading-relaxed">{{ $portfolio->description }}</p>

                @if($portfolio->features && count($portfolio->features))
                <h2 class="mt-10 font-display text-xl font-semibold text-text">Features</h2>
                <ul class="mt-4 list-disc space-y-2 pl-5 text-text-muted">
                    @foreach($portfolio->features as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                @endif

                @if($portfolio->challenges)
                <h2 class="mt-10 font-display text-xl font-semibold text-text">Challenges</h2>
                <p class="mt-3 text-text-muted leading-relaxed">{{ $portfolio->challenges }}</p>
                @endif

                @if($portfolio->solutions)
                <h2 class="mt-10 font-display text-xl font-semibold text-text">Solutions</h2>
                <p class="mt-3 text-text-muted leading-relaxed">{{ $portfolio->solutions }}</p>
                @endif

                @if($portfolio->images && count($portfolio->images) > 1)
                <div class="mt-10 grid gap-4 sm:grid-cols-2">
                    @foreach(array_slice($portfolio->images, 1) as $image)
                    @php $src = filter_var($image, FILTER_VALIDATE_URL) ? $image : route('portfolio.image', basename($image)); @endphp
                    <img src="{{ $src }}" alt="" class="rounded-xl border border-border object-cover w-full" loading="lazy">
                    @endforeach
                </div>
                @endif
            </div>

            <aside class="fade-in-view space-y-6">
                @if($portfolio->main_image)
                @php $hero = filter_var($portfolio->main_image, FILTER_VALIDATE_URL) ? $portfolio->main_image : route('portfolio.image', basename($portfolio->main_image)); @endphp
                <img src="{{ $hero }}" alt="{{ $portfolio->title }}" class="rounded-2xl border border-border w-full" width="400" height="225">
                @endif

                <div class="card-surface p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-text-dim">Technologies</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($portfolio->technologies ?? [] as $tech)
                        <span class="badge-uv">{{ $tech }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="card-surface p-5 text-sm text-text-muted space-y-2">
                    @if($portfolio->client)<p><strong class="text-text">Client:</strong> {{ $portfolio->client }}</p>@endif
                    @if($portfolio->duration_months)<p><strong class="text-text">Duration:</strong> {{ $portfolio->duration_months }} months</p>@endif
                    <p><strong class="text-text">Published:</strong> {{ $portfolio->created_at->format('M Y') }}</p>
                </div>

                <a href="{{ route('portfolio') }}" class="btn-secondary w-full text-center">← All projects</a>
            </aside>
        </div>
    </div>
</section>
@endsection
