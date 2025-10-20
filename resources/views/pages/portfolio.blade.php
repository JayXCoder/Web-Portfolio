@extends('layouts.app')

@section('title', 'Portfolio - Jawahar Ganesh @ Jay')
@section('description', 'Explore my portfolio of web development projects, mobile applications, AI/ML solutions, and embedded systems. See my latest work in Laravel, React, Python, and JavaScript development.')
@section('keywords', 'Portfolio, Web Development Projects, Mobile Apps, AI/ML Projects, Embedded Systems, Laravel Projects, React Projects, Python Projects, JavaScript Projects, Software Development')
@section('og_type', 'website')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<section class="section" style="padding-top: 150px;">
    <div class="container">
        <h2 class="section-title">My Portfolio</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <p style="color: var(--text-secondary); font-size: 1.1rem;">
                    A showcase of my diverse projects spanning AI/ML, cybersecurity, full-stack development, 
                    hardware/IoT, and infrastructure solutions. Each project represents my journey as a 
                    self-taught programmer and Computer Engineer.
                </p>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div class="portfolio-filter">
                    <button class="filter-btn active" data-filter="all">All Projects</button>
                    <button class="filter-btn" data-filter="AI/ML">AI/ML</button>
                    <button class="filter-btn" data-filter="Hardware/IoT">Hardware/IoT</button>
                    <button class="filter-btn" data-filter="Web Development">Web Development</button>
                    <button class="filter-btn" data-filter="Cybersecurity">Cybersecurity</button>
                    <button class="filter-btn" data-filter="Mobile Development">Mobile</button>
                    <button class="filter-btn" data-filter="Infrastructure">Infrastructure</button>
                </div>
            </div>
        </div>

        <!-- Portfolio Grid -->
        <div class="row" id="portfolio-grid">
            @forelse($portfolioItems as $item)
            <div class="col-lg-4 col-md-6 mb-4 portfolio-item" data-category="{{ $item->category }}">
                <div class="portfolio-card">
                    <div class="portfolio-image">
                        @if($item->main_image)
                            @if(filter_var($item->main_image, FILTER_VALIDATE_URL))
                                <img src="{{ $item->main_image }}" alt="{{ $item->title }}" class="img-fluid">
                            @else
                                <img src="{{ route('portfolio.image', basename($item->main_image)) }}" alt="{{ $item->title }}" class="img-fluid">
                            @endif
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="text-muted">No Image</p>
                            </div>
                        @endif
                        <div class="portfolio-overlay">
                            <div class="portfolio-overlay-content">
                                <h5>{{ $item->title }}</h5>
                                <p>{{ $item->category }}</p>
                                <a href="{{ route('portfolio.item', $item->slug) }}" class="btn btn-primary-custom">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @if($item->is_featured)
                        <div class="featured-badge">
                            <i class="fas fa-star"></i> Featured
                        </div>
                        @endif
                    </div>
                    <div class="portfolio-content">
                        <h4 class="portfolio-title">{{ $item->title }}</h4>
                        <p class="portfolio-description">{{ Str::limit($item->short_description, 120) }}</p>
                        <div class="portfolio-technologies">
                            @if($item->technologies)
                                @foreach($item->technologies as $tech)
                                <span class="tech-tag">{{ $tech }}</span>
                                @endforeach
                            @endif
                        </div>
                        <div class="portfolio-meta">
                            <span class="portfolio-date">{{ $item->created_at->format('M Y') }}</span>
                            <a href="{{ route('portfolio.item', $item->slug) }}" class="portfolio-link">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="no-portfolio-message">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Portfolio Items Yet</h4>
                    <p class="text-muted">Portfolio items will appear here once they are added through the admin panel.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Call to Action -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="card-custom">
                    <i class="fas fa-rocket card-icon"></i>
                    <h3 class="card-title">Interested in Collaborating?</h3>
                    <p class="card-text">
                        I'm always excited to work on new projects and explore innovative solutions. 
                        Whether you have an idea for an AI system, need cybersecurity expertise, 
                        or want to build something amazing together, let's connect!
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-envelope me-2"></i>Get In Touch
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .portfolio-filter {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .filter-btn {
        background: transparent;
        border: 2px solid var(--border-color);
        color: var(--text-secondary);
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--accent-primary);
        border-color: var(--accent-primary);
        color: white;
        transform: translateY(-2px);
    }

    .portfolio-card {
        background: var(--dark-secondary);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid var(--border-color);
    }

    .portfolio-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border-color: var(--accent-primary);
    }

    .portfolio-image {
        position: relative;
        overflow: hidden;
        height: 250px;
    }

    .portfolio-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .portfolio-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(139, 92, 246, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .portfolio-card:hover .portfolio-overlay {
        opacity: 1;
    }

    .portfolio-card:hover .portfolio-image img {
        transform: scale(1.1);
    }

    .portfolio-overlay-content {
        text-align: center;
        color: white;
    }

    .portfolio-overlay-content h5 {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .portfolio-overlay-content p {
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent-secondary);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .portfolio-content {
        padding: 1.5rem;
    }

    .portfolio-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .portfolio-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .portfolio-technologies {
        margin-bottom: 1rem;
    }

    .tech-tag {
        display: inline-block;
        background: var(--dark-tertiary);
        color: var(--accent-primary);
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-right: 5px;
        margin-bottom: 5px;
        border: 1px solid var(--accent-primary);
    }

    .portfolio-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .portfolio-date {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .portfolio-link {
        color: var(--accent-primary);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .portfolio-link:hover {
        color: var(--accent-secondary);
    }

    .portfolio-item.hide {
        display: none;
    }

    @media (max-width: 768px) {
        .portfolio-filter {
            gap: 0.5rem;
        }

        .filter-btn {
            padding: 6px 15px;
            font-size: 0.9rem;
        }

        .portfolio-image {
            height: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Portfolio filtering
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const portfolioItems = document.querySelectorAll('.portfolio-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');

                portfolioItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.classList.remove('hide');
                    } else {
                        item.classList.add('hide');
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
