@extends('layouts.app')

@section('title', 'About | Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container max-w-3xl">
        <h1 class="section-title fade-in-view">About me</h1>
        <p class="section-subtitle fade-in-view">Engineer by degree. Builder by obsession.</p>

        <div class="mt-10 space-y-6 fade-in-view">
            <article class="card-surface p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-uv-bright">My Journey</h2>
                <p class="mt-4 text-text-muted leading-relaxed">
                    My path wasn't a straight line. It was a deliberate evolution. I started as a
                    <strong class="text-text">Computer Engineer</strong>, grounded in hardware, systems, and the fundamentals of how machines think.
                    From there I became a <strong class="text-text">coder</strong>, then a
                    <strong class="text-text">Full-Stack Developer</strong> shipping real web products with Laravel, React, and Python.
                    Today I'm an <strong class="text-text">AI Engineer</strong>, building intelligent systems with local LLMs, automation, and production ML. Same builder, now working higher up the stack.
                </p>

                <ol class="journey-timeline mt-8 space-y-0">
                    <li class="journey-step">
                        <span class="journey-step-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342"/></svg>
                        </span>
                        <div>
                            <h3 class="font-display font-semibold text-text">Computer Engineer</h3>
                            <p class="mt-1 text-sm text-text-muted">UniMAP: systems, hardware, and engineering fundamentals.</p>
                        </div>
                    </li>
                    <li class="journey-step">
                        <span class="journey-step-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>
                        </span>
                        <div>
                            <h3 class="font-display font-semibold text-text">Full-Stack Developer</h3>
                            <p class="mt-1 text-sm text-text-muted">Self-taught builder on web apps, APIs, and production deployments.</p>
                        </div>
                    </li>
                    <li class="journey-step">
                        <span class="journey-step-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-display font-semibold text-text">AI Engineer</h3>
                            <p class="mt-1 text-sm text-text-muted">LLMs, ML pipelines, and intelligent tooling. This is where I am now.</p>
                        </div>
                    </li>
                </ol>
            </article>
            <article class="card-surface p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-uv-bright">Education</h2>
                <p class="mt-4 text-text-muted leading-relaxed">
                    <strong class="text-text">Bachelor Honours in Computer Engineering</strong>, with a hardware and systems foundation extended through hands-on software across web, AI, and embedded domains.
                </p>
            </article>
            <article class="card-surface p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-uv-bright">Approach</h2>
                <p class="mt-4 text-text-muted leading-relaxed">
                    Practical, entrepreneurial, and full-stack. I care about shipping, security, and maintainability as much as the demo.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection
