@extends('layouts.admin')

@section('title', 'Add User')
@section('page_heading', 'Add user')

@section('content')
<form method="POST" action="{{ route('admin.users.store') }}" class="card-surface max-w-md space-y-4 p-6">
    @csrf
    @include('admin.users._form')
    <button type="submit" class="btn-primary">Create user</button>
</form>
@endsection
