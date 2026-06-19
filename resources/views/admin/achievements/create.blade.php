@extends('layouts.admin')

@section('title', 'Create Achievement')
@section('page_heading', 'Create achievement')

@section('content')
<x-admin.page-header title="New achievement" description="Add a certificate, award, competition win, stage moment, or other milestone." />

@include('admin.achievements._form', [
    'achievement' => null,
    'action' => route('admin.achievements.store'),
    'method' => 'POST',
])
@endsection
