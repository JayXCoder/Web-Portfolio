<!DOCTYPE html>
<html lang="en" class="bg-oled">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="meVhqb01r1XAY5th9eDkkdckwOdorDK8IKrxiVh3DDo" />
    <title>@yield('title', 'Jawahar Ganesh @ Jay | Portfolio')</title>

    <meta name="description" content="@yield('description', 'Professional portfolio of Jawahar Ganesh @ Jay. Full-Stack Developer, Software Engineer, and Technical Consultant.')">
    <meta name="keywords" content="@yield('keywords', 'Jawahar Ganesh, JayXCoder, Full-Stack Developer, Laravel, React, Python')">
    <meta name="author" content="Jawahar Ganesh @ Jay">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#000000">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Jawahar Ganesh @ Jay | Portfolio')">
    <meta property="og:description" content="@yield('description', 'Professional portfolio of Jawahar Ganesh @ Jay.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="JayXCoder Portfolio">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Jawahar Ganesh @ Jay | Portfolio')">
    <meta name="twitter:description" content="@yield('description', 'Professional portfolio of Jawahar Ganesh @ Jay.')">

    @yield('structured_data')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23000' rx='8'/%3E%3Crect x='8' y='8' width='84' height='84' fill='none' stroke='%23bf00ff' stroke-width='2' rx='6'/%3E%3Ctext x='50' y='42' font-family='monospace' font-size='20' font-weight='bold' text-anchor='middle' fill='%23fff'%3EJXG%3C/text%3E%3Ctext x='50' y='62' font-family='monospace' font-size='12' text-anchor='middle' fill='%23a855f7'%3E%3E_%3C/text%3E%3C/svg%3E">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-dvh flex-col">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-uv focus:px-4 focus:py-2 focus:text-white">Skip to main content</a>

    <header class="fixed inset-x-0 top-0 z-50 border-b border-border/80 bg-oled/90 backdrop-blur-md">
        <div class="site-container flex h-16 flex-wrap items-center justify-between gap-2 lg:h-[4.25rem] lg:flex-nowrap">
            <a href="{{ route('home') }}" class="group flex items-center gap-2 font-display text-lg font-bold text-uv-bright transition hover:text-uv-glow" aria-label="JayXCoder home">
                <span class="site-logo-badge" data-logo-cursor>
                    <span class="site-logo-text">JXG</span><span class="site-logo-cursor" aria-hidden="true"></span>
                </span>
                <span class="hidden sm:inline">JayXCoder</span>
            </a>

            <button id="nav-toggle" type="button" class="inline-flex min-h-11 min-w-11 cursor-pointer items-center justify-center rounded-xl border border-border text-text lg:hidden" aria-expanded="false" aria-controls="nav-menu" aria-label="Toggle navigation">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <nav id="nav-menu" class="hidden basis-full flex-col gap-1 border-t border-border bg-oled py-4 lg:flex lg:basis-auto lg:flex-row lg:items-center lg:gap-1 lg:border-0 lg:bg-transparent lg:py-0" aria-label="Main">
                @php
                    $links = [
                        ['route' => 'home', 'label' => 'Home'],
                        ['route' => 'about', 'label' => 'About'],
                        ['route' => 'skills', 'label' => 'Skills'],
                        ['route' => 'achievements', 'label' => 'Achievements'],
                        ['route' => 'projects', 'label' => 'Projects'],
                        ['route' => 'portfolio', 'label' => 'Portfolio'],
                        ['route' => 'experience', 'label' => 'Experience'],
                        ['route' => 'contact', 'label' => 'Contact'],
                        ['route' => 'chat', 'label' => 'AI Chat'],
                    ];
                @endphp
                @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="nav-link {{ request()->routeIs($link['route']) ? 'nav-link-active' : '' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('admin.login') }}" class="nav-link mt-2 lg:mt-0">
                    <span class="text-uv-bright">Admin</span>
                </a>
            </nav>
        </div>
    </header>

    <main id="main-content" class="flex-1 pt-16 lg:pt-[4.25rem]">
        @if(session('success'))
            <div class="site-container pt-4">
                <div class="rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-green-300" role="status">{{ session('success') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="mt-auto border-t border-border bg-surface">
        <div class="glow-line"></div>
        <div class="site-container py-10">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-text-muted">&copy; {{ date('Y') }} Jawahar Ganesh @ Jay</p>
                <div class="flex flex-wrap gap-4 text-sm text-text-dim" id="visitor-stats-footer">
                    <span><strong class="text-text-muted">Visitors:</strong> <span id="totalVisitors">-</span></span>
                    <span><strong class="text-text-muted">Views:</strong> <span id="totalPageViews">-</span></span>
                    <span><strong class="text-text-muted">Today:</strong> <span id="todayVisitors">-</span></span>
                    <span class="inline-flex items-center gap-1.5 text-uv-bright">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-uv" aria-hidden="true"></span> Live
                    </span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
