@extends('layouts.admin')

@section('title', 'Portfolios')
@section('page_heading', 'Portfolios')

@section('content')
<x-admin.page-header title="Portfolios" description="Manage published projects and drafts.">
    <x-slot:actions>
        <a href="{{ route('admin.portfolios.create') }}" class="btn-primary">+ Create</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="card-surface overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="border-b border-border bg-surface-muted/50 text-text-dim">
                <tr>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($portfolios as $portfolio)
                <tr class="hover:bg-surface-muted/30 transition">
                    <td class="px-4 py-3">
                        <p class="font-medium text-text">{{ $portfolio->title }}</p>
                        <p class="text-xs text-text-dim">{{ $portfolio->slug }}</p>
                    </td>
                    <td class="px-4 py-3"><span class="badge-uv">{{ $portfolio->category }}</span></td>
                    <td class="px-4 py-3">
                        @if($portfolio->is_published)<span class="text-success text-xs">Published</span>@else<span class="text-warning text-xs">Draft</span>@endif
                        @if($portfolio->is_featured)<span class="ml-2 text-uv-bright text-xs">★</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('portfolio.item', $portfolio->slug) }}" target="_blank" class="btn-ghost inline-flex min-h-9 py-1 text-xs">View</a>
                        <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="btn-secondary inline-flex min-h-9 py-1 text-xs">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-text-muted">No portfolios. <a href="{{ route('admin.portfolios.create') }}" class="text-uv-bright">Create one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
