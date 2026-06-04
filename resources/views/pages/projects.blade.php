@extends('layouts.app')

@section('title', 'Projects — Jawahar Ganesh @ Jay')

@section('content')
<section class="section-pad">
    <div class="site-container text-center">
        <h1 class="section-title fade-in-view">Projects</h1>
        <p class="section-subtitle mx-auto fade-in-view">Highlights from my portfolio — case studies with stack, challenges, and outcomes.</p>
        <a href="{{ route('portfolio') }}" class="btn-primary mt-8 inline-flex fade-in-view">Browse full portfolio</a>
    </div>
</section>
@endsection
