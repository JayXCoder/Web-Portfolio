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
