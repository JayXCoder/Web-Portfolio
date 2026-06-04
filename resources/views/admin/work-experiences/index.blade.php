@extends('layouts.admin')

@section('title', 'Work Experience')
@section('page_heading', 'Experience')

@section('content')
<x-admin.page-header title="Work experience">
    <x-slot:actions>
        <a href="{{ route('admin.work-experiences.create') }}" class="btn-primary">+ Add</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="space-y-3">
    @forelse($workExperiences as $exp)
    <div class="card-surface flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="font-medium text-text">{{ $exp->position }}</p>
            <p class="text-sm text-uv-bright">{{ $exp->company }}</p>
        </div>
        <a href="{{ route('admin.work-experiences.edit', $exp) }}" class="btn-secondary text-sm">Edit</a>
    </div>
    @empty
    <p class="text-text-muted">No entries yet.</p>
    @endforelse
</div>
@endsection
