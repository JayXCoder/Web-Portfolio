@extends('layouts.admin')

@section('title', 'Achievements')
@section('page_heading', 'Achievements')

@section('content')
<x-admin.page-header title="Achievements" description="Manage certificates and credentials shown on the public page.">
    <x-slot:actions>
        <a href="{{ route('admin.achievements.create') }}" class="btn-primary">+ Create</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="card-surface overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="border-b border-border bg-surface-muted/50 text-text-dim">
                <tr>
                    <th class="px-4 py-3 font-medium w-16">Order</th>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="px-4 py-3 font-medium">Organization</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($achievements as $achievement)
                <tr class="hover:bg-surface-muted/30 transition">
                    <td class="px-4 py-3 font-mono text-text-dim">{{ $achievement->sort_order }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-text">{{ $achievement->title }}</p>
                        @if($achievement->issued_date)
                        <p class="text-xs text-text-dim">Issued {{ $achievement->issued_date->format('M Y') }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3"><span class="badge-uv">{{ $achievement->organization }}</span></td>
                    <td class="px-4 py-3">
                        @if($achievement->is_published)
                        <span class="text-success text-xs">Published</span>
                        @else
                        <span class="text-warning text-xs">Draft</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.achievements.edit', $achievement) }}" class="btn-secondary inline-flex min-h-9 py-1 text-xs">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-text-muted">No achievements. <a href="{{ route('admin.achievements.create') }}" class="text-uv-bright">Create one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
