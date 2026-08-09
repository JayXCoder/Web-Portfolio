@extends('layouts.app')

@section('title', 'Home | Jawahar Ganesh @ Jay')
@section('description', 'Full-Stack Developer & Computer Engineer. Laravel, React, Python, AI/ML, cybersecurity, embedded systems.')

@section('structured_data')
<script type="application/ld+json">
{"@@context":"https://schema.org","@@type":"Person","name":"Jawahar Ganesh @ Jay","alternateName":"JayXCoder","jobTitle":"Full-Stack Developer","url":"{{ url('/') }}"}
</script>
@endsection

@section('content')
@php
    $fallbackMissions = [
        ['meta' => 'AI / ML', 'title' => 'OpenChat', 'desc' => 'Self-hosted streaming chat with multi-provider routing and persistent sessions.', 'url' => route('portfolio')],
        ['meta' => 'Security', 'title' => 'SentinelX', 'desc' => 'Enterprise intelligence agents turning public signals into risk scores and alerts.', 'url' => route('portfolio')],
        ['meta' => 'Hardware', 'title' => 'ATV Pesticide Sprayer', 'desc' => 'Award-backed automation for smallholder farms — from field hardware to ops software.', 'url' => route('achievements')],
    ];
@endphp

<div class="home-scroll" data-home-scroll>
    {{-- 01 / Cinematic HUD hero --}}
    <section class="home-hero" aria-label="Introduction" data-home-hero>
        <div class="home-hero-field" aria-hidden="true">
            <canvas class="home-hero-canvas" data-hero-canvas width="1200" height="800"></canvas>
            <div class="home-hero-grid"></div>
            <div class="home-hero-orb home-hero-orb--a"></div>
            <div class="home-hero-orb home-hero-orb--b"></div>
            <div class="home-hero-orb home-hero-orb--c"></div>
            <div class="home-hero-scan"></div>
            <div class="home-hero-vignette"></div>
        </div>

        <div class="home-hero-hud site-container" aria-hidden="true">
            <div class="home-hud-chip"><span class="home-hud-dot"></span> SYS · ONLINE</div>
            <div class="home-hud-chip home-hud-chip--right">LAT 5.41 · LNG 100.33 · MY</div>
        </div>

        <div class="animated-text-container home-hero-words" aria-hidden="true">
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

        <div class="site-container relative z-10 flex min-h-[100dvh] flex-col justify-center gap-10 pb-14 pt-28 lg:grid lg:grid-cols-2 lg:items-center lg:gap-14 lg:pb-20">
            <div>
                <div class="home-hero-topline fade-in-view">
                    <span>Computer Engineer</span>
                    <span class="home-hero-sep" aria-hidden="true"></span>
                    <span>AI Builder</span>
                    <span class="home-hero-sep" aria-hidden="true"></span>
                    <span>UniMAP</span>
                </div>
                <p class="home-hero-brand fade-in-view" data-parallax="0.12">JayXCoder</p>
                <h1 class="home-hero-title fade-in-view" data-parallax="0.06">
                    Ocean-scale ambition.<br class="hidden sm:block">
                    <span class="home-hero-title-accent">Laptop-scale shipping.</span>
                </h1>
                <p class="home-hero-lede fade-in-view">
                    Jawahar Ganesh @ Jay designs and ships real systems across web, agentic AI, cybersecurity, and hardware — then grounds an on-site assistant in that same work.
                </p>
                <div class="home-hero-cta fade-in-view">
                    <a href="{{ route('portfolio') }}" class="btn-primary">Explore the missions</a>
                    <a href="{{ route('chat') }}" class="btn-secondary">Interrogate the agent</a>
                </div>

                <dl class="home-hero-stats fade-in-view" data-counters>
                    <div>
                        <dt>Shipped systems</dt>
                        <dd><span data-count="{{ $stats['projects'] }}">0</span>+</dd>
                    </div>
                    <div>
                        <dt>Proof & awards</dt>
                        <dd><span data-count="{{ max($stats['achievements'], 1) }}">0</span></dd>
                    </div>
                    <div>
                        <dt>Stack depth</dt>
                        <dd><span data-count="{{ $stats['skills'] }}">0</span></dd>
                    </div>
                    <div>
                        <dt>Roles in field</dt>
                        <dd><span data-count="{{ max($stats['roles'], 1) }}">0</span></dd>
                    </div>
                </dl>
            </div>

            <div class="fade-in-view home-hero-terminal-wrap" style="transition-delay: 120ms">
                <div class="hero-terminal home-hero-terminal">
                    <div class="hero-terminal-header">
                        <span class="hero-terminal-dot hero-terminal-dot-red" aria-hidden="true"></span>
                        <span class="hero-terminal-dot hero-terminal-dot-yellow" aria-hidden="true"></span>
                        <span class="hero-terminal-dot hero-terminal-dot-green" aria-hidden="true"></span>
                        <span class="ml-2 text-xs text-text-muted">jay@devbox:~</span>
                    </div>
                    <span id="languageIndicator" class="hero-terminal-lang">Python</span>
                    <p class="hero-terminal-prompt-line" aria-hidden="true">
                        <span class="mk-plain">jay@devbox</span><span class="mk-type">:~</span><span class="mk-keyword">$</span>
                        <span class="hero-terminal-cursor" aria-hidden="true"></span>
                    </p>
                    <pre id="typedCode" class="hero-terminal-code hero-terminal-code--monokai" aria-live="polite"></pre>
                </div>
                <p class="home-hero-scroll-hint mt-8 lg:mt-10" aria-hidden="true">
                    <span class="home-hero-scroll-line"></span>
                    Descend the stack
                </p>
            </div>
        </div>
    </section>

    {{-- Infinite skill marquee --}}
    <div class="home-marquee" aria-hidden="true" data-marquee>
        <div class="home-marquee-track">
            @foreach(array_merge($marqueeSkills, $marqueeSkills) as $skill)
                <span class="home-marquee-item">{{ $skill }}</span>
            @endforeach
        </div>
    </div>

    {{-- 02 / Signal statements --}}
    <section class="home-signals" aria-label="Focus areas">
        <div class="site-container">
            <p class="home-kicker fade-in-view">No matter the unknown</p>
            <ul class="home-signal-list" data-signal-list>
                <li class="home-signal" data-signal>The stack behind the product.</li>
                <li class="home-signal" data-signal>The model that stayed local.</li>
                <li class="home-signal" data-signal>The sensor that made it real.</li>
                <li class="home-signal" data-signal>The exploit that never shipped.</li>
                <li class="home-signal" data-signal>The deploy that held under load.</li>
                <li class="home-signal" data-signal>The award that proved the field build.</li>
            </ul>
            <p class="home-signal-close fade-in-view">I find it. Then I build it. Then I index it for the agent.</p>
        </div>
    </section>

    {{-- 03 / Sticky chapters with richer stage --}}
    <section class="home-chapters" data-chapters aria-label="How I work">
        <div class="home-chapters-sticky">
            <div class="site-container home-chapters-frame">
                <div class="home-chapters-copy">
                    <p class="home-kicker">Operating modes</p>
                    <div class="home-chapter-panels" data-chapter-panels>
                        <article class="home-chapter is-active" data-chapter="0">
                            <h2 class="home-chapter-title">Build in public depth.</h2>
                            <p class="home-chapter-body">Laravel, React, Python, and local LLMs — full systems with admin, auth, queues, and deployment, not slide-deck demos.</p>
                            <ul class="home-chapter-points">
                                <li>Production web apps & APIs with real operators</li>
                                <li>Agentic RAG chat grounded in this portfolio</li>
                                <li>Dashboards, OCR pipelines, and SaaS assessments</li>
                            </ul>
                            <a href="{{ route('portfolio') }}" class="home-chapter-cta">Browse shipped work →</a>
                        </article>
                        <article class="home-chapter" data-chapter="1" hidden>
                            <h2 class="home-chapter-title">Detect before it breaks.</h2>
                            <p class="home-chapter-body">Security is a build requirement: CSRF, HTTPS, role gates, and threat-aware defaults on every public surface.</p>
                            <ul class="home-chapter-points">
                                <li>Web pentest mindset on forms & admin</li>
                                <li>Hardened auth and access control</li>
                                <li>Live visitor analytics without selling souls</li>
                            </ul>
                            <a href="{{ route('about') }}" class="home-chapter-cta">Read the approach →</a>
                        </article>
                        <article class="home-chapter" data-chapter="2" hidden>
                            <h2 class="home-chapter-title">Ship to the edge.</h2>
                            <p class="home-chapter-body">Docker on Portainer, Arduino in the field, FPGA in the lab — software that still works when the problem leaves the browser.</p>
                            <ul class="home-chapter-points">
                                <li>Containerized production deploys</li>
                                <li>IoT sensing & embedded control</li>
                                <li>Award-backed hardware innovations</li>
                            </ul>
                            <a href="{{ route('achievements') }}" class="home-chapter-cta">See field proof →</a>
                        </article>
                    </div>
                    <div class="home-chapter-tabs" role="tablist" aria-label="Capability chapters">
                        <button type="button" class="home-chapter-tab is-active" data-chapter-tab="0" role="tab" aria-selected="true">Build</button>
                        <button type="button" class="home-chapter-tab" data-chapter-tab="1" role="tab" aria-selected="false">Secure</button>
                        <button type="button" class="home-chapter-tab" data-chapter-tab="2" role="tab" aria-selected="false">Deploy</button>
                    </div>
                </div>
                <div class="home-chapters-visual" aria-hidden="true">
                    <div class="home-chapter-stage" data-chapter-stage data-stage="0">
                        <span class="home-chapter-stage-code" data-stage-label>01 / BUILD</span>
                        <div class="home-chapter-stage-ring"></div>
                        <div class="home-chapter-stage-ring home-chapter-stage-ring--outer"></div>
                        <div class="home-chapter-stage-core"></div>
                        <ul class="home-chapter-stage-tags" data-stage-tags>
                            <li>Laravel</li><li>React</li><li>Ollama</li><li>Queues</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="home-chapters-progress" data-chapter-progress aria-hidden="true"><span></span></div>
        </div>
        <div class="home-chapters-spacer" data-chapter-spacer aria-hidden="true"></div>
    </section>

    {{-- 04 / Mission rail with tech chips --}}
    <section class="home-missions section-pad" aria-label="Selected projects">
        <div class="site-container">
            <div class="fade-in-view flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="home-kicker">Mission after mission</p>
                    <h2 class="home-section-title">Work proven in the wild.</h2>
                </div>
                <a href="{{ route('portfolio') }}" class="text-sm text-uv-bright hover:text-uv-glow">Full archive →</a>
            </div>
        </div>

        <div class="home-mission-rail mt-10" data-mission-rail>
            <div class="home-mission-track" data-mission-track>
                @forelse($featuredProjects as $i => $project)
                    <a href="{{ route('portfolio.item', $project) }}" class="home-mission-card" style="--delay: {{ $i * 40 }}ms">
                        <div class="home-mission-card-top">
                            <span class="home-mission-meta">{{ $project->category ?: 'Project' }}</span>
                            <span class="home-mission-index">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="home-mission-title">{{ $project->title }}</h3>
                        <p class="home-mission-desc">{{ Str::limit($project->short_description ?: $project->description, 120) }}</p>
                        @if(!empty($project->technologies))
                            <div class="home-mission-tags">
                                @foreach(array_slice($project->technologies ?? [], 0, 4) as $tech)
                                    <span>{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif
                        <span class="home-mission-cta">Open mission →</span>
                    </a>
                @empty
                    @foreach($fallbackMissions as $i => $mission)
                        <a href="{{ $mission['url'] }}" class="home-mission-card">
                            <div class="home-mission-card-top">
                                <span class="home-mission-meta">{{ $mission['meta'] }}</span>
                                <span class="home-mission-index">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <h3 class="home-mission-title">{{ $mission['title'] }}</h3>
                            <p class="home-mission-desc">{{ $mission['desc'] }}</p>
                            <span class="home-mission-cta">Open mission →</span>
                        </a>
                    @endforeach
                @endforelse
                <a href="{{ route('skills') }}" class="home-mission-card home-mission-card--accent">
                    <div class="home-mission-card-top">
                        <span class="home-mission-meta">Stack map</span>
                        <span class="home-mission-index">∞</span>
                    </div>
                    <h3 class="home-mission-title">The living tech tree</h3>
                    <p class="home-mission-desc">Languages, frameworks, AI tooling, security, DevOps, and hardware — connected like a constellation.</p>
                    <span class="home-mission-cta">Enter the tree →</span>
                </a>
            </div>
        </div>
    </section>

    {{-- 05 / Proof: awards + roles --}}
    <section class="home-proof section-pad border-t border-border" aria-label="Proof">
        <div class="site-container">
            <div class="grid gap-12 lg:grid-cols-2">
                <div class="fade-in-view">
                    <p class="home-kicker">Field credentials</p>
                    <h2 class="home-section-title">Awards & certificates.</h2>
                    <p class="mt-3 text-sm text-text-muted">Pulled live from the achievements archive — not marketing filler.</p>
                    <ul class="home-proof-list mt-8">
                        @forelse($achievements as $achievement)
                            <li>
                                <a href="{{ route('achievements') }}" class="home-proof-item">
                                    <span class="home-proof-type">{{ $achievement->typeLabel() }}</span>
                                    <span class="home-proof-title">{{ $achievement->title }}</span>
                                    <span class="home-proof-meta">{{ $achievement->organization }}@if($achievement->issued_date) · {{ $achievement->issued_date->format('Y') }}@endif</span>
                                </a>
                            </li>
                        @empty
                            <li>
                                <a href="{{ route('achievements') }}" class="home-proof-item">
                                    <span class="home-proof-type">Stage win</span>
                                    <span class="home-proof-title">Thailand Award for Best International Invention & Innovation</span>
                                    <span class="home-proof-meta">NRCT · MTE 2026 · ATV pesticide sprayer</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('achievements') }}" class="home-proof-item">
                                    <span class="home-proof-type">Credly</span>
                                    <span class="home-proof-title">IBM SkillsBuild cybersecurity & cloud credentials</span>
                                    <span class="home-proof-meta">Verified badges on Credly</span>
                                </a>
                            </li>
                        @endforelse
                    </ul>
                    <a href="{{ route('achievements') }}" class="btn-secondary mt-6 inline-flex">All achievements</a>
                </div>
                <div class="fade-in-view" style="transition-delay:80ms">
                    <p class="home-kicker">In the arena</p>
                    <h2 class="home-section-title">Roles that ship.</h2>
                    <p class="mt-3 text-sm text-text-muted">Current and recent work — AI engineering and full-stack delivery.</p>
                    <ul class="home-timeline mt-8">
                        @forelse($experiences as $exp)
                            <li class="home-timeline-item">
                                <span class="home-timeline-dot" aria-hidden="true"></span>
                                <div>
                                    <p class="home-timeline-role">{{ $exp->position }}</p>
                                    <p class="home-timeline-company">{{ $exp->company }}</p>
                                    <p class="home-timeline-meta">
                                        {{ $exp->start_date?->format('M Y') }}
                                        –
                                        {{ $exp->is_current ? 'Present' : $exp->end_date?->format('M Y') }}
                                        @if($exp->location) · {{ $exp->location }}@endif
                                    </p>
                                </div>
                            </li>
                        @empty
                            <li class="home-timeline-item">
                                <span class="home-timeline-dot" aria-hidden="true"></span>
                                <div>
                                    <p class="home-timeline-role">AI Engineer (Solution)</p>
                                    <p class="home-timeline-company">Maistorage Technology Sdn Bhd</p>
                                    <p class="home-timeline-meta">Aug 2026 – Present · Puchong</p>
                                </div>
                            </li>
                            <li class="home-timeline-item">
                                <span class="home-timeline-dot" aria-hidden="true"></span>
                                <div>
                                    <p class="home-timeline-role">Full-stack & AI Developer</p>
                                    <p class="home-timeline-company">Biztory Cloud Accounting</p>
                                    <p class="home-timeline-meta">Oct 2025 – May 2026</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                    <a href="{{ route('experience') }}" class="btn-secondary mt-6 inline-flex">Full experience</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 06 / Domains --}}
    <section class="home-domains section-pad border-t border-border" data-domains aria-label="Domains">
        <div class="site-container">
            <p class="home-kicker fade-in-view">Engineered for every need</p>
            <h2 class="home-section-title fade-in-view">Three surfaces. One builder.</h2>
            <p class="mt-4 max-w-2xl text-text-muted fade-in-view">Like fleet platforms with different mission profiles — pick a lane and the stack reconfigures.</p>

            <div class="home-domain-tabs mt-10 fade-in-view" role="tablist" aria-label="Domains">
                <button type="button" class="home-domain-tab is-active" data-domain-tab="web" role="tab" aria-selected="true">Web</button>
                <button type="button" class="home-domain-tab" data-domain-tab="ai" role="tab" aria-selected="false">AI</button>
                <button type="button" class="home-domain-tab" data-domain-tab="hw" role="tab" aria-selected="false">Hardware</button>
            </div>

            <div class="home-domain-panels mt-8">
                <article class="home-domain-panel is-active fade-in-view" data-domain-panel="web">
                    <div class="home-domain-glow" aria-hidden="true"></div>
                    <h3 class="font-display text-3xl font-bold text-text sm:text-4xl">Web & APIs</h3>
                    <p class="mt-4 max-w-xl text-text-muted">Laravel backends, React and Angular UIs, secure admin panels, and cloud-ready APIs that survive real users.</p>
                    <ul class="home-domain-stats">
                        <li><span>Stack</span>Laravel · React · Next.js · MySQL · Redis</li>
                        <li><span>Focus</span>Auth, CRUD, queues, dashboards, SaaS</li>
                        <li><span>Ship</span>Docker · Nginx · Portainer · CI</li>
                    </ul>
                    <a href="{{ route('portfolio') }}" class="btn-secondary mt-8 inline-flex">See web projects</a>
                </article>
                <article class="home-domain-panel fade-in-view" data-domain-panel="ai" hidden>
                    <div class="home-domain-glow home-domain-glow--ai" aria-hidden="true"></div>
                    <h3 class="font-display text-3xl font-bold text-text sm:text-4xl">AI / ML</h3>
                    <p class="mt-4 max-w-xl text-text-muted">Local LLMs, RAG pipelines, OCR, and agent workflows — intelligence grounded in your documents, not vibes.</p>
                    <ul class="home-domain-stats">
                        <li><span>Stack</span>Ollama · Qwen · PyTorch · LangChain</li>
                        <li><span>Focus</span>Retrieval, reasoning, automation, OCR</li>
                        <li><span>Ship</span>Self-hosted · queued jobs · GPU-aware</li>
                    </ul>
                    <a href="{{ route('chat') }}" class="btn-secondary mt-8 inline-flex">Talk to the agent</a>
                </article>
                <article class="home-domain-panel fade-in-view" data-domain-panel="hw" hidden>
                    <div class="home-domain-glow home-domain-glow--hw" aria-hidden="true"></div>
                    <h3 class="font-display text-3xl font-bold text-text sm:text-4xl">Hardware & IoT</h3>
                    <p class="mt-4 max-w-xl text-text-muted">Sensors, embedded control, and FPGA experiments — when the product has to live outside the laptop.</p>
                    <ul class="home-domain-stats">
                        <li><span>Stack</span>Arduino · Pi · ESP32 · Verilog</li>
                        <li><span>Focus</span>Sensing, control, edge links, crypto engines</li>
                        <li><span>Ship</span>Field demos · award-backed builds</li>
                    </ul>
                    <a href="{{ route('portfolio') }}" class="btn-secondary mt-8 inline-flex">See hardware work</a>
                </article>
            </div>
        </div>
    </section>

    {{-- 07 / Climax CTA --}}
    <section class="home-close section-pad" aria-label="Next step">
        <div class="site-container">
            <div class="home-close-panel fade-in-view">
                <p class="home-kicker">Take action</p>
                <h2 class="home-close-title">Make contact.<br>Plan the build.<br><span class="text-uv-bright">Ship the unknown.</span></h2>
                <p class="mt-5 max-w-lg text-text-muted">Whether you need a full-stack product, an on-prem AI assistant, or hardware that talks to software — start a conversation.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-primary">Contact Jay</a>
                    <a href="{{ route('chat') }}" class="btn-secondary">Ask the portfolio agent</a>
                    <a href="{{ route('experience') }}" class="btn-ghost">Experience</a>
                </div>
            </div>

            <div class="home-telemetry fade-in-view" data-home-telemetry aria-live="polite">
                <div>
                    <p class="home-telemetry-label">Distance scrolled</p>
                    <p class="home-telemetry-value"><span data-scroll-distance>0.0</span><span class="home-telemetry-unit">screens</span></p>
                </div>
                <div>
                    <p class="home-telemetry-label">Scroll velocity</p>
                    <p class="home-telemetry-value"><span data-scroll-speed>0.00</span><span class="home-telemetry-unit">×</span></p>
                </div>
                <p class="home-telemetry-note" data-scroll-note>Idle — systems standing by</p>
            </div>
        </div>
    </section>
</div>
@endsection
