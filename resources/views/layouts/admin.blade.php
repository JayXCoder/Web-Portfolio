<!DOCTYPE html>
<html lang="en" class="bg-oled">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — JayXCoder')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-dvh bg-oled text-text">
    <div id="admin-sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/60 lg:hidden" aria-hidden="true"></div>

    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-border bg-surface transition-transform duration-200 lg:translate-x-0" aria-label="Admin navigation">
        <div class="flex h-16 items-center gap-2 border-b border-border px-4">
            <span class="rounded-lg border border-uv/40 bg-oled px-2 py-0.5 font-mono text-sm text-uv-bright">JXG</span>
            <span class="font-display font-semibold text-text">Admin</span>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-3">
            @php
                $nav = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.portfolios', 'label' => 'Portfolios', 'match' => 'admin.portfolios*', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['route' => 'admin.work-experiences', 'label' => 'Experience', 'match' => 'admin.work-experiences*', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6'],
                    ['route' => 'admin.contacts', 'label' => 'Contacts', 'match' => 'admin.contacts*', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['route' => 'admin.visitors', 'label' => 'Analytics', 'match' => 'admin.visitors*', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ];
                if(auth()->user()?->isAdmin()) {
                    $nav[] = ['route' => 'admin.users', 'label' => 'Users', 'match' => 'admin.users*', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];
                    $nav[] = ['route' => 'admin.profile', 'label' => 'Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'];
                }
            @endphp
            @foreach($nav as $item)
                @php $active = request()->routeIs($item['match'] ?? $item['route']); @endphp
                <a href="{{ route($item['route']) }}" class="admin-sidebar-link {{ $active ? 'admin-sidebar-link-active' : '' }}">
                    <svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item['icon'] }}"/></svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-border p-3">
            <a href="{{ route('home') }}" class="admin-sidebar-link mb-1" target="_blank" rel="noopener">View site</a>
            <form method="POST" action="{{ route('admin.logout.post') }}">
                @csrf
                <button type="submit" class="admin-sidebar-link w-full text-left text-danger">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="lg:pl-64">
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-border bg-oled/95 px-4 backdrop-blur-md sm:px-6">
            <button id="admin-sidebar-toggle" type="button" class="inline-flex min-h-11 min-w-11 cursor-pointer items-center justify-center rounded-xl border border-border lg:hidden" aria-label="Open menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="font-display text-lg font-semibold text-text sm:text-xl">@yield('page_heading', 'Dashboard')</h1>
            <span class="hidden text-sm text-text-dim sm:block">{{ auth()->user()->name ?? '' }}</span>
        </header>

        <div class="p-4 sm:p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-green-300" role="status">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-red-300" role="alert">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
