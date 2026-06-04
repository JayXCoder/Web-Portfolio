@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')

@section('content')
<x-admin.page-header title="Overview" description="Portfolio, contacts, and visitor metrics at a glance." />

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="stat-card">
        <p class="text-sm text-text-dim">Portfolios</p>
        <p class="mt-1 font-display text-3xl font-bold text-text">{{ $stats['total_portfolios'] }}</p>
        <p class="mt-1 text-xs text-text-muted">{{ $stats['published_portfolios'] }} published · {{ $stats['featured_portfolios'] }} featured</p>
    </div>
    <div class="stat-card">
        <p class="text-sm text-text-dim">Contacts</p>
        <p class="mt-1 font-display text-3xl font-bold text-text">{{ $stats['total_contacts'] }}</p>
        <p class="mt-1 text-xs text-warning">{{ $stats['unread_contacts'] }} unread</p>
    </div>
    <div class="stat-card">
        <p class="text-sm text-text-dim">Unique visitors</p>
        <p class="mt-1 font-display text-3xl font-bold text-uv-bright">{{ $stats['total_visitors'] ?? 0 }}</p>
    </div>
    <div class="stat-card">
        <p class="text-sm text-text-dim">Page views</p>
        <p class="mt-1 font-display text-3xl font-bold text-text">{{ $stats['total_page_views'] ?? 0 }}</p>
        <p class="mt-1 text-xs text-text-muted">{{ $stats['today_visitors'] ?? 0 }} today</p>
    </div>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <div class="card-surface p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-semibold text-text">Recent portfolios</h3>
            <a href="{{ route('admin.portfolios.create') }}" class="text-sm text-uv-bright hover:underline">+ New</a>
        </div>
        <ul class="space-y-3">
            @forelse($recentPortfolios as $p)
            <li class="flex items-center justify-between gap-2 text-sm">
                <a href="{{ route('admin.portfolios.edit', $p) }}" class="text-text hover:text-uv-bright truncate">{{ $p->title }}</a>
                <span class="shrink-0 badge-uv">{{ $p->is_published ? 'Live' : 'Draft' }}</span>
            </li>
            @empty
            <li class="text-text-muted text-sm">No portfolios yet.</li>
            @endforelse
        </ul>
    </div>
    <div class="card-surface p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-semibold text-text">Recent contacts</h3>
            <a href="{{ route('admin.contacts') }}" class="text-sm text-uv-bright hover:underline">View all</a>
        </div>
        <ul class="space-y-3">
            @forelse($recentContacts as $c)
            <li class="text-sm">
                <a href="{{ route('admin.contacts.show', $c) }}" class="font-medium text-text hover:text-uv-bright">{{ $c->name }}</a>
                <p class="text-text-dim truncate">{{ $c->email }}</p>
            </li>
            @empty
            <li class="text-text-muted text-sm">No messages yet.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <a href="{{ route('admin.portfolios.create') }}" class="btn-primary">Create portfolio (AI)</a>
    <a href="{{ route('admin.visitors') }}" class="btn-secondary">Analytics</a>
</div>
@endsection
