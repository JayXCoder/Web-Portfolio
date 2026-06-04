@extends('layouts.app')

@section('title', 'Access denied')

@section('content')
<section class="flex min-h-[60dvh] items-center justify-center section-pad">
    <div class="text-center max-w-md">
        <h1 class="font-display text-3xl font-bold text-text">Access denied</h1>
        <p class="mt-3 text-text-muted">You don't have permission to view this area.</p>
        <a href="{{ route('admin.dashboard') }}" class="btn-primary mt-6 inline-flex">Back to dashboard</a>
    </div>
</section>
@endsection
