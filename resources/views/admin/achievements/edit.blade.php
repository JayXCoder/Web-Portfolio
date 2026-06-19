@extends('layouts.admin')

@section('title', 'Edit Achievement')
@section('page_heading', 'Edit achievement')

@section('content')
<x-admin.page-header :title="$achievement->title" description="Update certificate details and display order.">
    <x-slot:actions>
        <a href="{{ route('achievements') }}" target="_blank" class="btn-secondary">View live</a>
    </x-slot:actions>
</x-admin.page-header>

@include('admin.achievements._form', [
    'achievement' => $achievement,
    'action' => route('admin.achievements.update', $achievement),
    'method' => 'PUT',
])

<form method="POST" action="{{ route('admin.achievements.delete', $achievement) }}" class="mt-8 max-w-3xl" onsubmit="return confirm('Delete this achievement permanently?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-secondary border-danger/50 text-red-300 hover:border-danger">Delete achievement</button>
</form>
@endsection
