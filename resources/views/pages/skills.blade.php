@extends('layouts.app')

@section('title', 'Skills — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view">Skills</h1>
        <p class="section-subtitle fade-in-view">Technologies I use to ship end-to-end.</p>

        @php
            $groups = config('skills.groups', []);
        @endphp

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($groups as $title => $skills)
            <article class="card-surface fade-in-view p-6">
                <h2 class="font-display text-lg font-semibold text-uv-bright">{{ $title }}</h2>
                <ul class="mt-5 grid grid-cols-2 gap-2 sm:grid-cols-2">
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
