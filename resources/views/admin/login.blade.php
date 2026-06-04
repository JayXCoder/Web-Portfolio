@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<section class="flex min-h-[80dvh] items-center justify-center section-pad">
    <div class="site-container w-full max-w-md fade-in-view">
        <div class="card-surface p-8" style="box-shadow: var(--shadow-uv-sm)">
            <div class="text-center mb-8">
                <span class="inline-block rounded-lg border border-uv/40 bg-oled px-3 py-1 font-mono text-uv-bright">JXG</span>
                <h1 class="mt-4 font-display text-2xl font-bold text-text">Admin portal</h1>
                <p class="mt-2 text-sm text-text-muted">Sign in to manage your portfolio</p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="label-field">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="input-field @error('email') border-danger @enderror">
                    @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="label-field">Password</label>
                    <input type="password" id="password" name="password" required class="input-field @error('password') border-danger @enderror">
                    @error('password')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-text-muted cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-border text-uv focus:ring-uv">
                    Remember me
                </label>
                <button type="submit" class="btn-primary w-full">Sign in</button>
            </form>
            <p class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm text-text-dim hover:text-uv-bright">← Back to site</a>
            </p>
        </div>
    </div>
</section>
@endsection
