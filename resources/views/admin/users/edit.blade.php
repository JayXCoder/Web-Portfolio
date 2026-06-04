@extends('layouts.admin')

@section('title', 'Edit User')
@section('page_heading', 'Edit user')

@section('content')
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="card-surface max-w-md space-y-4 p-6">
    @csrf @method('PUT')
    @include('admin.users._form', ['user' => $user])
    <button type="submit" class="btn-primary">Save</button>
</form>
@endsection
