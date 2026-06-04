@extends('layouts.admin')

@section('title', 'Contacts')
@section('page_heading', 'Contacts')

@section('content')
<x-admin.page-header title="Contact messages" />

<div class="card-surface divide-y divide-border">
    @forelse($contacts as $contact)
    <a href="{{ route('admin.contacts.show', $contact) }}" class="flex flex-col gap-1 p-4 transition hover:bg-surface-muted/40 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="font-medium text-text">{{ $contact->name }} <span class="text-text-dim font-normal">· {{ $contact->email }}</span></p>
            <p class="text-sm text-text-muted line-clamp-1">{{ Str::limit($contact->message, 80) }}</p>
        </div>
        <span class="text-xs {{ $contact->is_read ? 'text-text-dim' : 'text-uv-bright' }}">{{ $contact->is_read ? 'Read' : 'Unread' }}</span>
    </a>
    @empty
    <p class="p-8 text-center text-text-muted">No messages.</p>
    @endforelse
</div>
@endsection
