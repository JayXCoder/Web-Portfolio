@extends('layouts.app')

@section('title', 'Achievements | Jawahar Ganesh @ Jay')
@section('description', 'Verified certificates and professional achievements from IBM SkillsBuild and other organizations.')

@section('content')
<section class="section-pad">
    <div class="site-container max-w-4xl">
        <h1 class="section-title fade-in-view">Achievements</h1>
        <p class="section-subtitle fade-in-view">Verified certificates, credentials, and the work behind them.</p>

        @if ($credlyProfile)
        <div class="credly-banner fade-in-view mt-10">
            <div class="credly-banner__content">
                <div class="credly-banner__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-7 w-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"/>
                    </svg>
                </div>
                <div>
                    <p class="credly-banner__label">Verified on Credly</p>
                    <p class="credly-banner__text">All badges are publicly verifiable on my Credly profile.</p>
                </div>
            </div>
            <a href="{{ $credlyProfile }}" target="_blank" rel="noopener noreferrer" class="btn-primary shrink-0">
                View Credly profile
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
        @endif

        <div class="mt-12 space-y-6">
            @forelse ($achievements as $achievement)
            <article class="achievement-card fade-in-view card-surface p-6 sm:p-8">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                    @if ($achievement->badgeUrl())
                    <img src="{{ $achievement->badgeUrl() }}"
                         alt="{{ $achievement->title }} badge"
                         class="achievement-card__badge h-20 w-20 shrink-0 rounded-xl border border-border object-contain bg-surface-muted p-2"
                         width="80"
                         height="80"
                         loading="lazy">
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h2 class="font-display text-xl font-semibold text-text">{{ $achievement->title }}</h2>
                                <p class="mt-1 text-uv-bright">{{ $achievement->organization }}</p>
                                @if ($achievement->issued_date)
                                <p class="mt-1 text-sm text-text-dim">Issued {{ $achievement->issued_date->format('M Y') }}</p>
                                @endif
                            </div>
                            @if ($achievement->credly_url)
                            <a href="{{ $achievement->credly_url }}" target="_blank" rel="noopener noreferrer" class="btn-secondary text-xs">
                                Verify badge
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            @endif
                        </div>

                        @if ($achievement->story)
                        <div class="mt-5">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-text-muted">What I did</h3>
                            <p class="mt-2 text-text-muted leading-relaxed">{{ $achievement->story }}</p>
                        </div>
                        @endif

                        @if ($achievement->project)
                        <div class="mt-4 rounded-xl border border-border/60 bg-surface-muted/50 px-4 py-3">
                            <h3 class="text-sm font-semibold text-uv-bright">Related project</h3>
                            <p class="mt-1 text-sm text-text-muted leading-relaxed">{{ $achievement->project }}</p>
                        </div>
                        @endif

                        @if ($achievement->skills)
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($achievement->skills as $skill)
                            <span class="badge-uv">{{ $skill }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <p class="text-center text-text-muted">No achievements listed yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
