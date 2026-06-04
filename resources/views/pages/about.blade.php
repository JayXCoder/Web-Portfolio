@extends('layouts.app')

@section('title', 'About — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container max-w-3xl">
        <h1 class="section-title fade-in-view">About me</h1>
        <p class="section-subtitle fade-in-view">Engineer by degree. Builder by obsession.</p>

        <div class="mt-10 space-y-6 fade-in-view">
            <article class="card-surface p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-uv-bright">My journey</h2>
                <p class="mt-4 text-text-muted leading-relaxed">
                    I'm a Computer Engineering graduate from UniMAP (Malaysia), and a self-taught programmer who learned by building — YouTube, docs, and countless side projects. I turn final-year ideas into real products.
                </p>
            </article>
            <article class="card-surface p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-uv-bright">Education</h2>
                <p class="mt-4 text-text-muted leading-relaxed">
                    <strong class="text-text">Bachelor Honours in Computer Engineering</strong> — hardware and systems foundation, extended through hands-on software across web, AI, and embedded domains.
                </p>
            </article>
            <article class="card-surface p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-uv-bright">Approach</h2>
                <p class="mt-4 text-text-muted leading-relaxed">
                    Practical, entrepreneurial, and full-stack — I care about shipping, security, and maintainability as much as the demo.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection
