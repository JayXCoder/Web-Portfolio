@extends('layouts.admin')

@section('title', 'Contacts')
@section('page_heading', 'Contacts')

@section('content')
<x-admin.page-header title="Contact messages" description="Select messages to delete in bulk." />

@if($contacts->isEmpty())
    <div class="card-surface p-8 text-center text-text-muted">No messages.</div>
@else
<form id="contacts-bulk-form" method="POST" action="{{ route('admin.contacts.bulk-delete') }}" class="space-y-4">
    @csrf
    @method('DELETE')

    <div class="flex flex-wrap items-center justify-between gap-3">
        <label class="inline-flex items-center gap-2 text-sm text-text-muted cursor-pointer">
            <input id="contacts-select-all" type="checkbox" class="rounded border-border text-uv focus:ring-uv">
            Select all
        </label>
        <button
            id="contacts-bulk-delete"
            type="submit"
            class="btn-secondary border-danger/50 text-red-300 hover:border-danger disabled:cursor-not-allowed disabled:opacity-40"
            disabled
        >
            Delete selected
        </button>
    </div>

    <div class="card-surface divide-y divide-border">
        @foreach($contacts as $contact)
            <div class="flex items-start gap-3 p-4 transition hover:bg-surface-muted/40 sm:items-center">
                <input
                    type="checkbox"
                    name="ids[]"
                    value="{{ $contact->id }}"
                    class="contact-checkbox mt-1 rounded border-border text-uv focus:ring-uv sm:mt-0"
                    aria-label="Select message from {{ $contact->name }}"
                >
                <a href="{{ route('admin.contacts.show', $contact) }}" class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium text-text">{{ $contact->name }} <span class="text-text-dim font-normal">· {{ $contact->email }}</span></p>
                        <p class="text-sm text-text-muted line-clamp-1">{{ Str::limit($contact->message, 80) }}</p>
                    </div>
                    <span class="mt-1 inline-block text-xs {{ $contact->is_read ? 'text-text-dim' : 'text-uv-bright' }} sm:mt-0">{{ $contact->is_read ? 'Read' : 'Unread' }}</span>
                </a>
            </div>
        @endforeach
    </div>
</form>

@push('scripts')
<script>
(() => {
    const form = document.getElementById('contacts-bulk-form');
    if (!form) return;

    const selectAll = document.getElementById('contacts-select-all');
    const deleteBtn = document.getElementById('contacts-bulk-delete');
    const boxes = () => Array.from(form.querySelectorAll('.contact-checkbox'));

    const sync = () => {
        const all = boxes();
        const checked = all.filter((box) => box.checked);
        deleteBtn.disabled = checked.length === 0;
        deleteBtn.textContent = checked.length > 0
            ? `Delete selected (${checked.length})`
            : 'Delete selected';
        if (selectAll) {
            selectAll.checked = all.length > 0 && checked.length === all.length;
            selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
        }
    };

    selectAll?.addEventListener('change', () => {
        boxes().forEach((box) => { box.checked = selectAll.checked; });
        sync();
    });

    form.addEventListener('change', (event) => {
        if (event.target.classList.contains('contact-checkbox')) sync();
    });

    form.addEventListener('submit', (event) => {
        const count = boxes().filter((box) => box.checked).length;
        if (count === 0) {
            event.preventDefault();
            return;
        }
        if (!confirm(`Delete ${count} selected message${count === 1 ? '' : 's'}? This cannot be undone.`)) {
            event.preventDefault();
        }
    });

    sync();
})();
</script>
@endpush
@endif
@endsection
