@extends('layouts.app')

@section('title', 'Experience — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container max-w-3xl">
        <h1 class="section-title fade-in-view">Experience</h1>
        <p class="section-subtitle fade-in-view">Professional roles and impact.</p>

        <div class="mt-12 space-y-6">
            @forelse($workExperiences as $exp)
            <article class="card-surface fade-in-view p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    @if($exp->company_logo)
                    @php $logo = filter_var($exp->company_logo, FILTER_VALIDATE_URL) ? $exp->company_logo : route('company.logo', basename($exp->company_logo)); @endphp
                    <img src="{{ $logo }}" alt="{{ $exp->company }}" class="h-14 w-14 rounded-xl border border-border object-contain bg-surface-muted p-2" width="56" height="56">
                    @endif
                    <div class="flex-1">
                        <h2 class="font-display text-xl font-semibold text-text">{{ $exp->position }}</h2>
                        <p class="text-uv-bright">{{ $exp->company }} · {{ $exp->employment_type }}</p>
                        <p class="mt-1 text-sm text-text-dim">
                            {{ $exp->start_date?->format('M Y') }} —
                            {{ $exp->is_current ? 'Present' : $exp->end_date?->format('M Y') }}
                            @if($exp->location) · {{ $exp->location }}@endif
                        </p>
                        <p class="mt-4 text-text-muted leading-relaxed">{{ $exp->description }}</p>
                        @if($exp->technologies)
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($exp->technologies as $tech)
                            <span class="badge-uv">{{ $tech }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <p class="text-center text-text-muted">No experience entries yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
