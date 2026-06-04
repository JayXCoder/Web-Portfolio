@extends('layouts.app')

@section('title', 'Contact — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container">
        <h1 class="section-title fade-in-view">Get in touch</h1>
        <p class="section-subtitle fade-in-view">Projects, collaborations, or technical discussions — I'd love to hear from you.</p>

        <div class="mt-12 grid gap-10 lg:grid-cols-2">
            <div class="fade-in-view space-y-4">
                <div class="card-surface p-5">
                    <p class="text-sm text-text-dim">Email</p>
                    <a href="mailto:jawaharganesh99jg@gmail.com" class="text-uv-bright hover:underline">jawaharganesh99jg@gmail.com</a>
                </div>
                <div class="card-surface p-5">
                    <p class="text-sm text-text-dim">LinkedIn</p>
                    <a href="https://linkedin.com/in/jay71" target="_blank" rel="noopener" class="text-uv-bright hover:underline">linkedin.com/in/jay71</a>
                </div>
                <div class="card-surface p-5">
                    <p class="text-sm text-text-dim">GitHub</p>
                    <a href="https://github.com/jawahar-ganesh" target="_blank" rel="noopener" class="text-uv-bright hover:underline">github.com/jawahar-ganesh</a>
                </div>
            </div>

            <form method="POST" action="{{ route('contact.submit') }}" class="card-surface fade-in-view p-6 sm:p-8 space-y-4">
                @csrf
                <div>
                    <label for="name" class="label-field">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input-field @error('name') border-danger @enderror">
                    @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="label-field">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input-field @error('email') border-danger @enderror">
                    @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="organization" class="label-field">Organization</label>
                        <input type="text" id="organization" name="organization" value="{{ old('organization') }}" class="input-field">
                    </div>
                    <div>
                        <label for="phone" class="label-field">Phone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="input-field">
                    </div>
                </div>
                <div>
                    <label for="message" class="label-field">Message *</label>
                    <textarea id="message" name="message" rows="5" required class="input-field min-h-[120px] @error('message') border-danger @enderror">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full">Send message</button>
            </form>
        </div>
    </div>
</section>
@endsection
