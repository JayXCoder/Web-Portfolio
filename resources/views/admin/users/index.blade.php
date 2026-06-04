@extends('layouts.admin')

@section('title', 'Users')
@section('page_heading', 'Users')

@section('content')
<x-admin.page-header title="Admin users">
    <x-slot:actions><a href="{{ route('admin.users.create') }}" class="btn-primary">+ Add user</a></x-slot:actions>
</x-admin.page-header>

<div class="card-surface divide-y divide-border">
    @foreach($users as $user)
    <div class="flex items-center justify-between gap-4 p-4">
        <div>
            <p class="font-medium text-text">{{ $user->name }}</p>
            <p class="text-sm text-text-dim">{{ $user->email }} · {{ $user->role }}</p>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary text-sm">Edit</a>
    </div>
    @endforeach
</div>
@endsection
