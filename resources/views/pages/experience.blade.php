@extends('layouts.app')

@section('title', 'Work Experience - Jawahar Ganesh @ Jay')
@section('description', 'My professional work experience as a Full-Stack Developer, Software Engineer, and Technical Consultant. Including roles at JayXCoder Dev and Echo Broadband with expertise in web development, mobile apps, AI/ML, and embedded systems.')
@section('keywords', 'Work Experience, Professional Experience, Full-Stack Developer, Software Engineer, Technical Consultant, JayXCoder Dev, Echo Broadband, Web Development, Mobile Apps, AI/ML, Embedded Systems')
@section('og_type', 'profile')

@section('content')
<section class="section" style="padding-top: 150px;">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="section-title">Work Experience</h1>
                <p class="section-subtitle">My professional journey and career milestones</p>
            </div>
        </div>

        <!-- Experience Timeline -->
        <div class="row">
            <div class="col-12">
                <div class="experience-timeline">
                    @forelse($workExperiences as $index => $experience)
                    <div class="timeline-item {{ $index % 2 == 0 ? 'left' : 'right' }}">
                        <div class="timeline-marker">
                            <div class="marker-dot {{ $experience->employment_type_color }} {{ $experience->is_current ? 'pulse' : '' }}"></div>
                            <div class="marker-line"></div>
                        </div>
                        
                        <div class="timeline-content">
                            <div class="experience-card {{ $experience->employment_type_color }}">
                                <div class="card-header">
                                    <div class="company-info">
                                        @if($experience->company_logo)
                                            @if(filter_var($experience->company_logo, FILTER_VALIDATE_URL))
                                                <img src="{{ $experience->company_logo }}" alt="{{ $experience->company }}" class="company-logo">
                                            @else
                                                <img src="{{ route('company.logo', basename($experience->company_logo)) }}" alt="{{ $experience->company }}" class="company-logo">
                                            @endif
                                        @else
                                            <div class="company-logo-placeholder">
                                                <i class="fas fa-building"></i>
                                            </div>
                                        @endif
                                        <div class="company-details">
                                            <h3 class="company-name">{{ $experience->company }}</h3>
                                            <h4 class="position-title">{{ $experience->position }}</h4>
                                        </div>
                                    </div>
                                    
                                    <div class="employment-badges">
                                        <span class="employment-type-badge {{ $experience->employment_type_color }}">
                                            {{ $experience->employment_type }}
                                        </span>
                                        @if($experience->is_current)
                                            <span class="status-badge neon-green pulse">
                                                <i class="fas fa-circle"></i> Current
                                            </span>
                                        @else
                                            <span class="status-badge neon-gray">
                                                <i class="fas fa-circle"></i> Ended
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="experience-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>{{ $experience->start_date->format('M Y') }} - {{ $experience->is_current ? 'Present' : $experience->end_date->format('M Y') }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $experience->duration }}</span>
                                        </div>
                                        @if($experience->location)
                                        <div class="meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $experience->location }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="experience-description">
                                        <p>{{ $experience->description }}</p>
                                    </div>
                                    
                                    @if($experience->responsibilities)
                                    <div class="responsibilities">
                                        <h5>Key Responsibilities:</h5>
                                        <div class="responsibilities-content">
                                            {!! nl2br(e($experience->responsibilities)) !!}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($experience->technologies && count($experience->technologies) > 0)
                                    <div class="technologies-section">
                                        <h5>Technologies & Tools:</h5>
                                        <div class="tech-tags">
                                            @foreach($experience->technologies as $tech)
                                            <span class="tech-tag">{{ $tech }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($experience->achievements && count($experience->achievements) > 0)
                                    <div class="achievements-section">
                                        <h5>Achievements:</h5>
                                        <ul class="achievements-list">
                                            @foreach($experience->achievements as $achievement)
                                            <li>{{ $achievement }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    
                                    @if($experience->skills_gained && count($experience->skills_gained) > 0)
                                    <div class="skills-section">
                                        <h5>Skills Gained:</h5>
                                        <div class="skills-tags">
                                            @foreach($experience->skills_gained as $skill)
                                            <span class="skill-tag">{{ $skill }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($experience->team_size || $experience->reporting_to)
                                    <div class="team-info">
                                        @if($experience->team_size)
                                        <div class="team-item">
                                            <i class="fas fa-users"></i>
                                            <span>Team Size: {{ $experience->team_size }}</span>
                                        </div>
                                        @endif
                                        @if($experience->reporting_to)
                                        <div class="team-item">
                                            <i class="fas fa-user-tie"></i>
                                            <span>Reporting to: {{ $experience->reporting_to }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="no-experience-message">
                        <div class="text-center">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Work Experience Yet</h4>
                            <p class="text-muted">Work experience entries will appear here once they are added through the admin panel.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="experience-cta">
                    <h3>Interested in Working Together?</h3>
                    <p>I'm always excited to take on new challenges and contribute to innovative projects. Let's discuss how we can work together!</p>
                    <div class="cta-buttons">
                        <a href="{{ route('contact') }}" class="btn btn-primary-custom me-3">
                            <i class="fas fa-envelope me-2"></i>Get in Touch
                        </a>
                        <a href="{{ route('portfolio') }}" class="btn btn-outline-custom">
                            <i class="fas fa-folder me-2"></i>View My Work
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .section-subtitle {
        font-size: 1.2rem;
        color: var(--text-secondary);
        margin-bottom: 2rem;
    }

    /* Neon Color Variables */
    :root {
        --neon-blue: #00d4ff;
        --neon-green: #00ff88;
        --neon-yellow: #ffff00;
        --neon-orange: #ff6600;
        --neon-purple: #ff00ff;
        --neon-gray: #888888;
    }

    /* Timeline Styles */
    .experience-timeline {
        position: relative;
        padding: 2rem 0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 3rem;
        display: flex;
        align-items: flex-start;
    }

    .timeline-item.left {
        flex-direction: row;
    }

    .timeline-item.right {
        flex-direction: row-reverse;
    }

    .timeline-marker {
        position: relative;
        flex-shrink: 0;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .marker-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid;
        background: var(--dark-bg);
        position: relative;
        z-index: 2;
        box-shadow: 0 0 20px currentColor;
    }

    .marker-dot.neon-blue {
        color: var(--neon-blue);
        border-color: var(--neon-blue);
    }

    .marker-dot.neon-green {
        color: var(--neon-green);
        border-color: var(--neon-green);
    }

    .marker-dot.neon-yellow {
        color: var(--neon-yellow);
        border-color: var(--neon-yellow);
    }

    .marker-dot.neon-orange {
        color: var(--neon-orange);
        border-color: var(--neon-orange);
    }

    .marker-dot.neon-purple {
        color: var(--neon-purple);
        border-color: var(--neon-purple);
    }

    .marker-dot.neon-gray {
        color: var(--neon-gray);
        border-color: var(--neon-gray);
    }

    .marker-dot.pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 20px currentColor; }
        50% { box-shadow: 0 0 30px currentColor, 0 0 40px currentColor; }
        100% { box-shadow: 0 0 20px currentColor; }
    }

    .marker-line {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 2px;
        height: 100px;
        background: linear-gradient(to bottom, var(--border-color), transparent);
        transform: translateX(-50%);
        z-index: 1;
    }

    .timeline-content {
        flex: 1;
        margin: 0 2rem;
    }

    /* Experience Card Styles */
    .experience-card {
        background: var(--dark-secondary);
        border-radius: 15px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .experience-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .experience-card.neon-blue:hover {
        border-color: var(--neon-blue);
        box-shadow: 0 20px 40px rgba(0, 212, 255, 0.2);
    }

    .experience-card.neon-green:hover {
        border-color: var(--neon-green);
        box-shadow: 0 20px 40px rgba(0, 255, 136, 0.2);
    }

    .experience-card.neon-yellow:hover {
        border-color: var(--neon-yellow);
        box-shadow: 0 20px 40px rgba(255, 255, 0, 0.2);
    }

    .experience-card.neon-orange:hover {
        border-color: var(--neon-orange);
        box-shadow: 0 20px 40px rgba(255, 102, 0, 0.2);
    }

    .experience-card.neon-purple:hover {
        border-color: var(--neon-purple);
        box-shadow: 0 20px 40px rgba(255, 0, 255, 0.2);
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .company-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .company-logo {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid var(--border-color);
    }

    .company-logo-placeholder {
        width: 60px;
        height: 60px;
        background: var(--dark-tertiary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 1.5rem;
    }

    .company-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .position-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--accent-primary);
        margin: 0;
    }

    .employment-badges {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .employment-type-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .employment-type-badge.neon-blue {
        background: rgba(0, 212, 255, 0.1);
        color: var(--neon-blue);
        border: 1px solid var(--neon-blue);
    }

    .employment-type-badge.neon-green {
        background: rgba(0, 255, 136, 0.1);
        color: var(--neon-green);
        border: 1px solid var(--neon-green);
    }

    .employment-type-badge.neon-yellow {
        background: rgba(255, 255, 0, 0.1);
        color: var(--neon-yellow);
        border: 1px solid var(--neon-yellow);
    }

    .employment-type-badge.neon-orange {
        background: rgba(255, 102, 0, 0.1);
        color: var(--neon-orange);
        border: 1px solid var(--neon-orange);
    }

    .employment-type-badge.neon-purple {
        background: rgba(255, 0, 255, 0.1);
        color: var(--neon-purple);
        border: 1px solid var(--neon-purple);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-badge.neon-green {
        background: rgba(0, 255, 136, 0.1);
        color: var(--neon-green);
        border: 1px solid var(--neon-green);
    }

    .status-badge.neon-gray {
        background: rgba(136, 136, 136, 0.1);
        color: var(--neon-gray);
        border: 1px solid var(--neon-gray);
    }

    .status-badge.pulse {
        animation: pulse 2s infinite;
    }

    .card-body {
        padding: 1.5rem;
    }

    .experience-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .meta-item i {
        color: var(--accent-primary);
        width: 16px;
    }

    .experience-description {
        margin-bottom: 1.5rem;
    }

    .experience-description p {
        color: var(--text-secondary);
        line-height: 1.7;
        margin: 0;
    }

    .responsibilities,
    .technologies-section,
    .achievements-section,
    .skills-section {
        margin-bottom: 1.5rem;
    }

    .responsibilities h5,
    .technologies-section h5,
    .achievements-section h5,
    .skills-section h5 {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .responsibilities-content {
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .tech-tags,
    .skills-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tech-tag,
    .skill-tag {
        background: var(--dark-tertiary);
        color: var(--accent-primary);
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        border: 1px solid var(--accent-primary);
        transition: all 0.3s ease;
    }

    .tech-tag:hover,
    .skill-tag:hover {
        background: var(--accent-primary);
        color: white;
        transform: translateY(-2px);
    }

    .achievements-list {
        list-style: none;
        padding: 0;
    }

    .achievements-list li {
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        position: relative;
        padding-left: 1.5rem;
    }

    .achievements-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--accent-primary);
        font-weight: bold;
    }

    .team-info {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .team-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .team-item i {
        color: var(--accent-primary);
    }

    .no-experience-message {
        text-align: center;
        padding: 4rem 2rem;
    }

    .experience-cta {
        background: var(--dark-secondary);
        padding: 3rem;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        text-align: center;
        margin-top: 3rem;
    }

    .experience-cta h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .experience-cta p {
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .timeline-item {
            flex-direction: column !important;
            align-items: center;
        }

        .timeline-content {
            margin: 1rem 0 0 0;
            width: 100%;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .employment-badges {
            align-items: flex-start;
            flex-direction: row;
            gap: 1rem;
        }

        .experience-meta {
            flex-direction: column;
            gap: 0.75rem;
        }

        .company-info {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush
@endsection
