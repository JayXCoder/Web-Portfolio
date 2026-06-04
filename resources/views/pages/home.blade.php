@extends('layouts.app')

@section('title', 'Home | Jawahar Ganesh @ Jay')
@section('description', 'Full-Stack Developer & Computer Engineer. Laravel, React, Python, AI/ML, cybersecurity, embedded systems.')

@section('structured_data')
<script type="application/ld+json">
{"@@context":"https://schema.org","@@type":"Person","name":"Jawahar Ganesh @ Jay","alternateName":"JayXCoder","jobTitle":"Full-Stack Developer","url":"{{ url('/') }}"}
</script>
@endsection

@section('content')
<section class="hero-home section-pad">
    <div class="hero-grid-bg pointer-events-none absolute inset-0" aria-hidden="true"></div>

    <div class="animated-text-container" aria-hidden="true">
        <div class="animated-text-wrap">
            <span id="animatedText" class="animated-text">Code</span>
            <img
                id="animatedFavicon"
                class="animated-favicon"
                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23000' rx='8'/%3E%3Crect x='8' y='8' width='84' height='84' fill='none' stroke='%23bf00ff' stroke-width='2' rx='6'/%3E%3Ctext x='50' y='42' font-family='monospace' font-size='20' font-weight='bold' text-anchor='middle' fill='%23fff'%3EJXG%3C/text%3E%3C/svg%3E"
                alt=""
                width="48"
                height="48"
            >
            <div id="shapeCircle" class="animated-shape shape-circle"></div>
            <div id="shapeTriangle" class="animated-shape shape-triangle"></div>
            <div id="shapeSquare" class="animated-shape shape-square"></div>
        </div>
    </div>

    <div class="site-container relative z-10">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="fade-in-view">
                <p class="badge-uv mb-4 w-fit">Computer Engineer · UniMAP · Self-taught builder</p>
                <h1 class="font-display text-4xl font-extrabold leading-tight tracking-tight text-text sm:text-5xl lg:text-6xl">
                    I build <span class="text-uv-bright" style="text-shadow: var(--shadow-uv-sm)">real systems</span> across web, AI & hardware.
                </h1>
                <p class="mt-6 max-w-xl text-base text-text-muted sm:text-lg">
                    Jawahar Ganesh (@ Jay / JayXCoder), Full-Stack Developer specializing in Laravel, React, Python, AI/ML, cybersecurity, and embedded systems.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('portfolio') }}" class="btn-primary">View portfolio</a>
                    <a href="{{ route('contact') }}" class="btn-secondary">Get in touch</a>
                </div>
            </div>

            <div class="fade-in-view flex justify-center lg:justify-end" style="transition-delay: 120ms">
                <div class="hero-terminal">
                    <div class="hero-terminal-header">
                        <span class="hero-terminal-dot hero-terminal-dot-red" aria-hidden="true"></span>
                        <span class="hero-terminal-dot hero-terminal-dot-yellow" aria-hidden="true"></span>
                        <span class="hero-terminal-dot hero-terminal-dot-green" aria-hidden="true"></span>
                        <span class="ml-2 text-xs text-text-muted">jay@portfolio:~</span>
                    </div>
                    <span id="languageIndicator" class="hero-terminal-lang">Python</span>
                    <p class="hero-terminal-prompt">$&gt;<span class="hero-terminal-cursor" aria-hidden="true"></span></p>
                    <pre id="typedCode" class="hero-terminal-code" aria-live="polite"></pre>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad border-t border-border bg-surface">
    <div class="site-container">
        <h2 class="section-title fade-in-view">What I do</h2>
        <p class="section-subtitle fade-in-view">From side-project ventures to production deployments.</p>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['title' => 'Web & APIs', 'desc' => 'Laravel backends, React frontends, secure admin panels.', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                ['title' => 'AI / ML', 'desc' => 'Local LLMs, automation pipelines, intelligent tooling.', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                ['title' => 'Embedded & IoT', 'desc' => 'Arduino, Raspberry Pi, sensors, and edge integrations.', 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ] as $card)
            <article class="card-surface-hover fade-in-view p-6">
                <svg class="mb-4 h-8 w-8 text-uv" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}"/></svg>
                <h3 class="font-display text-lg font-semibold text-text">{{ $card['title'] }}</h3>
                <p class="mt-2 text-sm text-text-muted">{{ $card['desc'] }}</p>
            </article>
            @endforeach
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('chat') }}" class="btn-secondary">Talk to my AI assistant →</a>
        </div>
    </div>
</section>
@endsection
