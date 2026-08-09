@extends('layouts.app')

@section('title', 'Home | Jawahar Ganesh @ Jay')
@section('description', 'Full-Stack Developer & Computer Engineer. Laravel, React, Python, AI/ML, cybersecurity, embedded systems.')

@section('structured_data')
<script type="application/ld+json">
{"@@context":"https://schema.org","@@type":"Person","name":"Jawahar Ganesh @ Jay","alternateName":"JayXCoder","jobTitle":"Full-Stack Developer","url":"{{ url('/') }}"}
</script>
@endsection

@section('content')
<div class="home-scroll" data-home-scroll>
    {{-- 01 / Full-bleed hero --}}
    <section class="home-hero" aria-label="Introduction">
        <div class="home-hero-field" aria-hidden="true">
            <div class="home-hero-grid"></div>
            <div class="home-hero-orb home-hero-orb--a"></div>
            <div class="home-hero-orb home-hero-orb--b"></div>
            <div class="home-hero-scan"></div>
        </div>

        <div class="site-container relative z-10 flex min-h-[100dvh] flex-col justify-end pb-16 pt-28 sm:pb-20 lg:justify-center lg:pb-24">
            <p class="home-hero-brand fade-in-view">JayXCoder</p>
            <h1 class="home-hero-title fade-in-view">
                Systems that<br class="hidden sm:block"> actually ship.
            </h1>
            <p class="home-hero-lede fade-in-view">
                Web, AI, and hardware — built end to end by Jawahar Ganesh @ Jay.
            </p>
            <div class="home-hero-cta fade-in-view">
                <a href="{{ route('portfolio') }}" class="btn-primary">Explore the work</a>
                <a href="{{ route('chat') }}" class="btn-secondary">Ask the agent</a>
            </div>
            <p class="home-hero-scroll-hint fade-in-view" aria-hidden="true">
                <span class="home-hero-scroll-line"></span>
                Scroll the mission
            </p>
        </div>
    </section>

    {{-- 02 / Signal statements (scroll-lit) --}}
    <section class="home-signals" aria-label="Focus areas">
        <div class="site-container">
            <p class="home-kicker fade-in-view">No matter the unknown</p>
            <ul class="home-signal-list" data-signal-list>
                <li class="home-signal" data-signal>The stack behind the product.</li>
                <li class="home-signal" data-signal>The model that stayed local.</li>
                <li class="home-signal" data-signal>The sensor that made it real.</li>
                <li class="home-signal" data-signal>The exploit that never shipped.</li>
                <li class="home-signal" data-signal>The deploy that held under load.</li>
            </ul>
            <p class="home-signal-close fade-in-view">I find it. Then I build it.</p>
        </div>
    </section>

    {{-- 03 / Sticky capability chapters --}}
    <section class="home-chapters" data-chapters aria-label="How I work">
        <div class="home-chapters-sticky">
            <div class="site-container home-chapters-frame">
                <div class="home-chapters-copy">
                    <p class="home-kicker">Operating modes</p>
                    <div class="home-chapter-panels" data-chapter-panels>
                        <article class="home-chapter is-active" data-chapter="0">
                            <h2 class="home-chapter-title">Build in public depth.</h2>
                            <p class="home-chapter-body">Laravel, React, Python, and local LLMs — full systems with admin, auth, queues, and deployment, not just demos.</p>
                            <ul class="home-chapter-points">
                                <li>Production web apps & APIs</li>
                                <li>Agentic RAG chat on this site</li>
                                <li>Dashboards operators can use</li>
                            </ul>
                        </article>
                        <article class="home-chapter" data-chapter="1" hidden>
                            <h2 class="home-chapter-title">Detect before it breaks.</h2>
                            <p class="home-chapter-body">Security and reliability are part of the build: CSRF, HTTPS, access control, and threat-aware defaults on every surface that faces the network.</p>
                            <ul class="home-chapter-points">
                                <li>Web pentest mindset</li>
                                <li>Hardened admin & auth flows</li>
                                <li>Observability & visitor analytics</li>
                            </ul>
                        </article>
                        <article class="home-chapter" data-chapter="2" hidden>
                            <h2 class="home-chapter-title">Ship to the edge.</h2>
                            <p class="home-chapter-body">From Docker on Portainer to Arduino and Raspberry Pi — software that meets hardware when the problem leaves the browser.</p>
                            <ul class="home-chapter-points">
                                <li>Containers & CI-friendly deploys</li>
                                <li>IoT sensing & embedded control</li>
                                <li>FPGA / crypto experiments</li>
                            </ul>
                        </article>
                    </div>
                    <div class="home-chapter-tabs" role="tablist" aria-label="Capability chapters">
                        <button type="button" class="home-chapter-tab is-active" data-chapter-tab="0" role="tab" aria-selected="true">Build</button>
                        <button type="button" class="home-chapter-tab" data-chapter-tab="1" role="tab" aria-selected="false">Secure</button>
                        <button type="button" class="home-chapter-tab" data-chapter-tab="2" role="tab" aria-selected="false">Deploy</button>
                    </div>
                </div>
                <div class="home-chapters-visual" aria-hidden="true">
                    <div class="home-chapter-stage" data-chapter-stage>
                        <span class="home-chapter-stage-code" data-stage-label>01 / BUILD</span>
                        <div class="home-chapter-stage-ring"></div>
                        <div class="home-chapter-stage-core"></div>
                    </div>
                </div>
            </div>
            <div class="home-chapters-progress" data-chapter-progress aria-hidden="true"><span></span></div>
        </div>
        <div class="home-chapters-spacer" data-chapter-spacer aria-hidden="true"></div>
    </section>

    {{-- 04 / Horizontal mission rail --}}
    <section class="home-missions section-pad" aria-label="Selected projects">
        <div class="site-container">
            <div class="fade-in-view flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="home-kicker">Mission after mission</p>
                    <h2 class="home-section-title">Work proven in the wild.</h2>
                </div>
                <p class="max-w-sm text-sm text-text-muted">Drag or scroll sideways. Each card is a shipped system — AI, web, security, or hardware.</p>
            </div>
        </div>

        <div class="home-mission-rail mt-10" data-mission-rail>
            <div class="home-mission-track" data-mission-track>
                @forelse($featuredProjects as $project)
                    <a href="{{ route('portfolio.item', $project) }}" class="home-mission-card">
                        <span class="home-mission-meta">{{ $project->category ?: 'Project' }}</span>
                        <h3 class="home-mission-title">{{ $project->title }}</h3>
                        <p class="home-mission-desc">{{ Str::limit($project->short_description ?: $project->description, 110) }}</p>
                        <span class="home-mission-cta">Open mission →</span>
                    </a>
                @empty
                    <a href="{{ route('portfolio') }}" class="home-mission-card">
                        <span class="home-mission-meta">Portfolio</span>
                        <h3 class="home-mission-title">Explore the full archive</h3>
                        <p class="home-mission-desc">AI/ML, cybersecurity, full-stack, hardware, and infrastructure — all in one place.</p>
                        <span class="home-mission-cta">View portfolio →</span>
                    </a>
                @endforelse
                <a href="{{ route('skills') }}" class="home-mission-card home-mission-card--accent">
                    <span class="home-mission-meta">Stack</span>
                    <h3 class="home-mission-title">The tech tree</h3>
                    <p class="home-mission-desc">Languages, frameworks, AI tooling, security, DevOps, and hardware — mapped end to end.</p>
                    <span class="home-mission-cta">View skills →</span>
                </a>
            </div>
        </div>
    </section>

    {{-- 05 / Domain platforms --}}
    <section class="home-domains section-pad border-t border-border" data-domains aria-label="Domains">
        <div class="site-container">
            <p class="home-kicker fade-in-view">Engineered for every need</p>
            <h2 class="home-section-title fade-in-view">Three surfaces. One builder.</h2>
            <p class="mt-4 max-w-2xl text-text-muted fade-in-view">Pick a lane — the stack changes, the shipping habit does not.</p>

            <div class="home-domain-tabs mt-10 fade-in-view" role="tablist" aria-label="Domains">
                <button type="button" class="home-domain-tab is-active" data-domain-tab="web" role="tab" aria-selected="true">Web</button>
                <button type="button" class="home-domain-tab" data-domain-tab="ai" role="tab" aria-selected="false">AI</button>
                <button type="button" class="home-domain-tab" data-domain-tab="hw" role="tab" aria-selected="false">Hardware</button>
            </div>

            <div class="home-domain-panels mt-8">
                <article class="home-domain-panel is-active fade-in-view" data-domain-panel="web">
                    <h3 class="font-display text-3xl font-bold text-text sm:text-4xl">Web & APIs</h3>
                    <p class="mt-4 max-w-xl text-text-muted">Laravel backends, React and Angular UIs, secure admin panels, and cloud-ready APIs that survive real users.</p>
                    <ul class="home-domain-stats">
                        <li><span>Stack</span>Laravel · React · Next.js · MySQL</li>
                        <li><span>Focus</span>Auth, CRUD, queues, dashboards</li>
                        <li><span>Ship</span>Docker · Nginx · Portainer</li>
                    </ul>
                    <a href="{{ route('portfolio') }}" class="btn-secondary mt-8 inline-flex">See web projects</a>
                </article>
                <article class="home-domain-panel fade-in-view" data-domain-panel="ai" hidden>
                    <h3 class="font-display text-3xl font-bold text-text sm:text-4xl">AI / ML</h3>
                    <p class="mt-4 max-w-xl text-text-muted">Local LLMs, RAG pipelines, OCR, and agent workflows — intelligence that stays grounded in your data.</p>
                    <ul class="home-domain-stats">
                        <li><span>Stack</span>Ollama · PyTorch · LangChain · Qwen</li>
                        <li><span>Focus</span>Retrieval, reasoning, automation</li>
                        <li><span>Ship</span>Self-hosted · GPU-aware · queued jobs</li>
                    </ul>
                    <a href="{{ route('chat') }}" class="btn-secondary mt-8 inline-flex">Talk to the agent</a>
                </article>
                <article class="home-domain-panel fade-in-view" data-domain-panel="hw" hidden>
                    <h3 class="font-display text-3xl font-bold text-text sm:text-4xl">Hardware & IoT</h3>
                    <p class="mt-4 max-w-xl text-text-muted">Sensors, embedded control, and FPGA experiments — when the product has to live outside the laptop.</p>
                    <ul class="home-domain-stats">
                        <li><span>Stack</span>Arduino · Pi · ESP32 · Verilog</li>
                        <li><span>Focus</span>Sensing, control, edge links</li>
                        <li><span>Ship</span>Field demos · award-backed builds</li>
                    </ul>
                    <a href="{{ route('portfolio') }}" class="btn-secondary mt-8 inline-flex">See hardware work</a>
                </article>
            </div>
        </div>
    </section>

    {{-- 06 / Closing CTA + scroll telemetry --}}
    <section class="home-close section-pad" aria-label="Next step">
        <div class="site-container">
            <div class="home-close-panel fade-in-view">
                <p class="home-kicker">Take action</p>
                <h2 class="home-close-title">Make contact.<br>Plan the build.<br>Ship the unknown.</h2>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-primary">Contact Jay</a>
                    <a href="{{ route('experience') }}" class="btn-secondary">View experience</a>
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
