@extends('layouts.app')

@section('title', 'Skills — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view">Skills</h1>
        <p class="section-subtitle fade-in-view">Technologies I use to ship end-to-end — from full-stack apps to AI systems.</p>

        @php
            $groups = config('skills.groups', []);
            $wideGroups = config('skills.wide_groups', []);
        @endphp

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($groups as $title => $skills)
            @php $isWide = in_array($title, $wideGroups, true); @endphp
            <article @class([
                'card-surface fade-in-view p-6',
                'md:col-span-2 xl:col-span-2' => $isWide,
            ])>
                <h2 class="font-display text-lg font-semibold text-uv-bright">{{ $title }}</h2>
                @if ($title === 'Full Stack')
                <p class="mt-1 text-xs text-text-dim">Stacks I build with — e.g. Next.js + FastAPI, Laravel + React, Node + Prisma.</p>
                @elseif ($title === 'AI / ML')
                <p class="mt-1 text-xs text-text-dim">Training, inference, vision, and local LLM tooling.</p>
                @endif
                <ul @class([
                    'mt-5 grid gap-2',
                    'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4' => $isWide,
                    'grid-cols-2' => ! $isWide,
                ])>
                    @foreach ($skills as $skill)
                    <li class="skill-chip">
                        <x-skill-icon
                            :slug="$skill['slug'] ?? null"
                            :color="$skill['color'] ?? 'a855f7'"
                            :label="$skill['label']"
                        />
                        <span class="skill-chip-label">{{ $skill['label'] }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
