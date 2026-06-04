@extends('layouts.admin')

@section('title', 'Contact')
@section('page_heading', 'Message')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.contacts') }}" class="btn-ghost mb-6 inline-flex">← Back</a>
    <div class="card-surface p-6 space-y-4">
        <p><strong class="text-text-dim">From</strong><br>{{ $contact->name }} &lt;{{ $contact->email }}&gt;</p>
        @if($contact->phone)<p><strong class="text-text-dim">Phone</strong><br>{{ $contact->phone }}</p>@endif
        <p><strong class="text-text-dim">Message</strong><br><span class="text-text-muted whitespace-pre-line">{{ $contact->message }}</span></p>
        <p class="text-xs text-text-dim">{{ $contact->created_at->format('M j, Y g:i A') }}</p>
    </div>
    <div class="mt-4 flex gap-2">
        @if(!$contact->is_read)
        <form method="POST" action="{{ route('admin.contacts.mark-read', $contact) }}">@csrf @method('PATCH')<button class="btn-primary">Mark read</button></form>
        @endif
        <form method="POST" action="{{ route('admin.contacts.delete', $contact) }}" onsubmit="return confirm('Delete?');">@csrf @method('DELETE')<button class="btn-secondary text-red-300">Delete</button></form>
    </div>
</div>
@endsection
