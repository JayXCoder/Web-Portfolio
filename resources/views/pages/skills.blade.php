@extends('layouts.app')

@section('title', 'Skills | Jawahar Ganesh @ Jay')

@section('content')
@php
    $aiTools = config('skills.ai_tools', []);
    $tree = config('skills.tree', []);
    $apex = $tree['apex'] ?? [];
    $stacks = $tree['stacks'] ?? [];
    $branches = $tree['branches'] ?? [];
    $foundation = $tree['foundation'] ?? [];
@endphp

<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view">Skills</h1>
        <p class="section-subtitle fade-in-view">AI tooling, full-stack delivery, and the tech tree that connects it all.</p>

        @php $credlyProfile = config('achievements.credly_profile'); @endphp
        @if ($credlyProfile)
        <div class="credly-banner credly-banner--compact fade-in-view mt-8">
            <div class="credly-banner__content">
                <div class="credly-banner__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"/>
                    </svg>
                </div>
                <div>
                    <p class="credly-banner__label">Credly credentials</p>
                    <p class="credly-banner__text">View my verified IBM SkillsBuild badges and certificates.</p>
                </div>
            </div>
            <a href="{{ $credlyProfile }}" target="_blank" rel="noopener noreferrer" class="btn-secondary shrink-0 text-sm">
                Open Credly profile
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
        @endif

        {{-- Horizontal AI productivity tools --}}
        <div class="skills-ai-row fade-in-view mt-10">
            <p class="skills-ai-row__label">Productive AI tools</p>
            <ul class="skills-ai-row__list" role="list">
                @foreach ($aiTools as $tool)
                <li class="skill-chip skill-chip--ai">
                    <x-skill-icon
                        :slug="$tool['slug'] ?? null"
                        :color="$tool['color'] ?? 'a855f7'"
                        :icon="$tool['icon'] ?? null"
                        :label="$tool['label']"
                    />
                    <span class="skill-chip-label">{{ $tool['label'] }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Skill tree --}}
        <div class="skills-tree fade-in-view mt-14">
            {{-- Apex: Full Stack --}}
            <div class="skills-tree__node skills-tree__node--apex card-surface">
                <h2 class="skills-tree__title">{{ $apex['title'] ?? 'Full Stack' }}</h2>
                @if (! empty($apex['subtitle']))
                <p class="skills-tree__subtitle">{{ $apex['subtitle'] }}</p>
                @endif
                <x-skill-chips :skills="$apex['skills'] ?? []" />
            </div>

            <div class="skills-tree__connector" aria-hidden="true"></div>

            {{-- Stacks layer --}}
            <div class="skills-tree__node skills-tree__node--stacks card-surface">
                <h2 class="skills-tree__title">{{ $stacks['title'] ?? 'Stacks' }}</h2>
                @if (! empty($stacks['subtitle']))
                <p class="skills-tree__subtitle">{{ $stacks['subtitle'] }}</p>
                @endif
                <x-skill-chips :skills="$stacks['skills'] ?? []" />
            </div>

            <div class="skills-tree__junction" aria-hidden="true"></div>

            {{-- Branch grid --}}
            <div class="skills-tree__branches">
                @foreach ($branches as $branch)
                <article class="skills-tree__node skills-tree__node--branch card-surface">
                    <h3 class="skills-tree__branch-title">{{ $branch['title'] }}</h3>
                    <x-skill-chips :skills="$branch['skills'] ?? []" dense />
                </article>
                @endforeach
            </div>

            <div class="skills-tree__connector" aria-hidden="true"></div>

            {{-- Foundation: Languages --}}
            <div class="skills-tree__node skills-tree__node--foundation card-surface">
                <h2 class="skills-tree__title">{{ $foundation['title'] ?? 'Languages' }}</h2>
                @if (! empty($foundation['subtitle']))
                <p class="skills-tree__subtitle">{{ $foundation['subtitle'] }}</p>
                @endif
                <x-skill-chips :skills="$foundation['skills'] ?? []" />
            </div>
        </div>
    </div>
</section>

@endsection
