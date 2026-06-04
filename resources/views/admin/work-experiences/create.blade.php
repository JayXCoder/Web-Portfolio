@extends('layouts.admin')

@section('title', 'Add Experience')
@section('page_heading', 'Add experience')

@section('content')
<x-admin.page-header title="New work experience" />
<p class="text-sm text-text-muted mb-6">Use the form below. Fields match your public experience page.</p>

<form method="POST" action="{{ route('admin.work-experiences.store') }}" enctype="multipart/form-data" class="card-surface max-w-3xl space-y-4 p-6">
    @csrf
    @include('admin.work-experiences._fields')
    <button type="submit" class="btn-primary">Save</button>
</form>
@endsection
