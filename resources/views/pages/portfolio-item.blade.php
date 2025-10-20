@extends('layouts.app')

@section('title', $portfolio->title . ' - Portfolio - Jawahar Ganesh @ Jay')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<section class="section" style="padding-top: 150px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--accent-primary);">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('portfolio') }}" style="color: var(--accent-primary);">Portfolio</a></li>
                <li class="breadcrumb-item active" style="color: var(--text-secondary);">{{ $portfolio->title }}</li>
            </ol>
        </nav>

        <!-- Project Header -->
        <div class="row">
            <div class="col-lg-8">
                <div class="project-header">
                    <div class="project-category">{{ $portfolio->category }}</div>
                    <h1 class="project-title">{{ $portfolio->title }}</h1>
                    <p class="project-description">{{ $portfolio->description }}</p>
                    
                    <div class="project-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $portfolio->created_at->format('M Y') }}</span>
                        </div>
                        @if($portfolio->client)
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>{{ $portfolio->client }}</span>
                        </div>
                        @endif
                        @if($portfolio->duration_months)
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $portfolio->duration_months }} months</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="project-sidebar">
                    <div class="sidebar-section">
                        <h4>Technologies Used</h4>
                        <div class="tech-list">
                            @if($portfolio->technologies)
                                @foreach($portfolio->technologies as $tech)
                                <span class="tech-badge">{{ $tech }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                    <div class="sidebar-section">
                        <h4>Project Details</h4>
                        <div class="project-details">
                            <div class="detail-item">
                                <strong>Category:</strong> {{ $portfolio->category }}
                            </div>
                            @if($portfolio->duration_months)
                            <div class="detail-item">
                                <strong>Duration:</strong> {{ $portfolio->duration_months }} months
                            </div>
                            @endif
                            @if($portfolio->client)
                            <div class="detail-item">
                                <strong>Client:</strong> {{ $portfolio->client }}
                            </div>
                            @endif
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <a href="{{ route('contact') }}" class="btn btn-primary-custom w-100">
                            <i class="fas fa-envelope me-2"></i>Discuss Similar Project
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Images -->
        @if($portfolio->images && count($portfolio->images) > 0)
        <div class="row mt-5">
            <div class="col-12">
                <div class="project-images-section">
                    <h3 class="section-subtitle">Project Screenshots</h3>
                    <div class="project-gallery">
                        @foreach($portfolio->images as $image)
                        <div class="gallery-item">
                            @if(filter_var($image, FILTER_VALIDATE_URL))
                                <img src="{{ $image }}" alt="Project Screenshot" class="img-fluid">
                            @else
                                <img src="{{ route('portfolio.image', basename($image)) }}" alt="Project Screenshot" class="img-fluid">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Project Overview -->
        <div class="row mt-5">
            <div class="col-lg-8">
                <div class="project-content-section">
                    <div class="project-content">
                        <h3 class="section-subtitle">Project Overview</h3>
                        <p>{{ $portfolio->description }}</p>

                        @if($portfolio->challenges)
                        <h3 class="section-subtitle">The Challenge</h3>
                        <p>{{ $portfolio->challenges }}</p>
                        @endif

                        @if($portfolio->solutions)
                        <h3 class="section-subtitle">The Solution</h3>
                        <p>{{ $portfolio->solutions }}</p>
                        @endif

                        @if($portfolio->features)
                        <h3 class="section-subtitle">Key Features</h3>
                        <ul class="feature-list">
                            @foreach($portfolio->features as $feature)
                            <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="project-stats">
                    <h4>Project Statistics</h4>
                    <div class="stats-grid">
                        @if($portfolio->duration_months)
                        <div class="stat-item">
                            <div class="stat-number">{{ $portfolio->duration_months }}+</div>
                            <div class="stat-label">Duration (months)</div>
                        </div>
                        @endif
                        @if($portfolio->technologies)
                        <div class="stat-item">
                            <div class="stat-number">{{ count($portfolio->technologies) }}+</div>
                            <div class="stat-label">Technologies</div>
                        </div>
                        @endif
                        <div class="stat-item">
                            <div class="stat-number">{{ $portfolio->category }}</div>
                            <div class="stat-label">Category</div>
                        </div>
                    </div>
                </div>

                <div class="project-links mt-4">
                    <h4>Related</h4>
                    <div class="related-links">
                        <a href="{{ route('portfolio') }}" class="related-link">
                            <i class="fas fa-arrow-left"></i> Back to Portfolio
                        </a>
                        <a href="{{ route('skills') }}" class="related-link">
                            <i class="fas fa-code"></i> View Skills
                        </a>
                        <a href="{{ route('contact') }}" class="related-link">
                            <i class="fas fa-envelope"></i> Contact Me
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="project-cta-section">
                    <div class="project-cta">
                        <h3>Interested in a Similar Project?</h3>
                        <p>I'm always excited to work on new challenges and bring innovative solutions to life. Let's discuss how we can work together!</p>
                        <div class="cta-buttons">
                            <a href="{{ route('contact') }}" class="btn btn-primary-custom me-3">
                                <i class="fas fa-envelope me-2"></i>Start a Project
                            </a>
                            <a href="{{ route('portfolio') }}" class="btn btn-outline-custom">
                                <i class="fas fa-folder me-2"></i>View More Projects
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-item a {
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--accent-secondary) !important;
    }

    .project-category {
        display: inline-block;
        background: var(--accent-primary);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .project-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .project-description {
        font-size: 1.1rem;
        color: var(--text-secondary);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .project-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
    }

    .meta-item i {
        color: var(--accent-primary);
    }

    .project-sidebar {
        background: var(--dark-secondary);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid var(--border-color);
        height: fit-content;
        position: sticky;
        top: 150px;
    }

    .sidebar-section {
        margin-bottom: 2rem;
    }

    .sidebar-section h4 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .tech-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tech-badge {
        background: var(--dark-tertiary);
        color: var(--accent-primary);
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        border: 1px solid var(--accent-primary);
        transition: all 0.3s ease;
    }

    .tech-badge:hover {
        background: var(--accent-primary);
        color: white;
    }

    .project-details .detail-item {
        margin-bottom: 0.8rem;
        color: var(--text-secondary);
    }

    .project-details .detail-item strong {
        color: var(--text-primary);
    }

    .section-subtitle {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        margin-top: 2.5rem;
        position: relative;
    }

    .section-subtitle::after {
        content: '';
        position: absolute;
        width: 40px;
        height: 3px;
        background: var(--accent-primary);
        bottom: -8px;
        left: 0;
        border-radius: 2px;
    }

    .project-images-section {
        padding: 0 2rem;
    }

    .project-content-section {
        padding: 0 2rem;
    }

    .project-cta-section {
        padding: 0 2rem;
    }

    .project-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .gallery-item {
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
    }

    .gallery-item img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .project-content p {
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .feature-list {
        list-style: none;
        padding: 0;
    }

    .feature-list li {
        color: var(--text-secondary);
        margin-bottom: 0.8rem;
        position: relative;
        padding-left: 1.5rem;
    }

    .feature-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--accent-primary);
        font-weight: bold;
    }

    .project-stats {
        background: var(--dark-secondary);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid var(--border-color);
        margin-top: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: var(--dark-tertiary);
        border-radius: 10px;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--accent-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .related-links {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .related-link {
        color: var(--text-secondary);
        text-decoration: none;
        padding: 0.8rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .related-link:hover {
        color: var(--accent-primary);
        border-color: var(--accent-primary);
        background: var(--dark-tertiary);
    }

    .project-cta {
        background: var(--dark-secondary);
        padding: 3rem;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        text-align: center;
        margin-top: 3rem;
    }

    .project-cta h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .project-cta p {
        color: var(--text-secondary);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .btn-outline-custom {
        background: transparent;
        border: 2px solid var(--accent-primary);
        color: var(--accent-primary);
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-outline-custom:hover {
        background: var(--accent-primary);
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .project-title {
            font-size: 2rem;
        }

        .project-meta {
            gap: 1rem;
        }

        .project-sidebar {
            position: static;
            margin-top: 2rem;
        }

        .project-gallery {
            grid-template-columns: 1fr;
        }

        .project-images-section,
        .project-content-section,
        .project-cta-section {
            padding: 0 1rem;
        }
    }
</style>
@endpush
@endsection
