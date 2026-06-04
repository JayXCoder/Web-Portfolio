@extends('layouts.admin')

@section('title', 'Profile')
@section('page_heading', 'Profile')

@section('content')
<form method="POST" action="{{ route('admin.profile') }}" class="card-surface max-w-md space-y-4 p-6">
    @csrf @method('PUT')
    <div><label class="label-field">Name</label><input name="name" value="{{ old('name', $user->name) }}" required class="input-field"></div>
    <div><label class="label-field">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input-field"></div>
    <div><label class="label-field">Current password *</label><input type="password" name="current_password" required class="input-field" autocomplete="current-password"></div>
    <div><label class="label-field">New password</label><input type="password" name="new_password" class="input-field" autocomplete="new-password"></div>
    <div><label class="label-field">Confirm new password</label><input type="password" name="new_password_confirmation" class="input-field"></div>
    <button type="submit" class="btn-primary">Update profile</button>
</form>
@endsection
