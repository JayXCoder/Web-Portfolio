@extends('layouts.admin')

@section('title', 'Edit Experience')
@section('page_heading', 'Edit experience')

@section('content')
<x-admin.page-header :title="$workExperience->position" />

<form method="POST" action="{{ route('admin.work-experiences.update', $workExperience) }}" enctype="multipart/form-data" class="card-surface max-w-3xl space-y-4 p-6">
    @csrf
    @method('PUT')
    @include('admin.work-experiences._fields', ['workExperience' => $workExperience])
    <button type="submit" class="btn-primary">Update</button>
</form>

<form method="POST" action="{{ route('admin.work-experiences.delete', $workExperience) }}" class="mt-4" onsubmit="return confirm('Delete?');">
    @csrf @method('DELETE')
    <button type="submit" class="btn-secondary text-red-300">Delete</button>
</form>
@endsection
