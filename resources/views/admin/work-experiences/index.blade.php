@extends('layouts.app')

@section('title', 'Work Experience Management - Admin - Jawahar Ganesh @ Jay')

@section('content')
<div class="container-fluid" style="padding-top: 100px; padding-bottom: 2rem;">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-briefcase me-2"></i>
                            Work Experience Management
                        </h1>
                        <p class="page-subtitle">Manage your professional work experience entries</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.work-experiences.create') }}" class="btn btn-primary-custom">
                            <i class="fas fa-plus me-2"></i>Add Work Experience
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Work Experience Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Work Experiences ({{ $workExperiences->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($workExperiences->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Position</th>
                                        <th>Employment Type</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Published</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workExperiences as $experience)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($experience->company_logo)
                                                    @if(filter_var($experience->company_logo, FILTER_VALIDATE_URL))
                                                        <img src="{{ $experience->company_logo }}" alt="{{ $experience->company }}" class="company-logo-small me-2">
                                                    @else
                                                        <img src="{{ route('company.logo', basename($experience->company_logo)) }}" alt="{{ $experience->company }}" class="company-logo-small me-2">
                                                    @endif
                                                @else
                                                    <div class="company-logo-placeholder-small me-2">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                @endif
                                                <span class="fw-semibold">{{ $experience->company }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $experience->position }}</td>
                                        <td>
                                            <span class="employment-type-badge {{ $experience->employment_type_color }}">
                                                {{ $experience->employment_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="duration-info">
                                                <div class="duration-text">{{ $experience->duration }}</div>
                                                <small class="text-muted">
                                                    {{ $experience->start_date->format('M Y') }} - 
                                                    {{ $experience->is_current ? 'Present' : $experience->end_date->format('M Y') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($experience->is_current)
                                                <span class="status-badge neon-green pulse">
                                                    <i class="fas fa-circle"></i> Current
                                                </span>
                                            @else
                                                <span class="status-badge neon-gray">
                                                    <i class="fas fa-circle"></i> Ended
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($experience->is_published)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Published
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-eye-slash"></i> Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.work-experiences.edit', $experience) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.work-experiences.delete', $experience) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this work experience?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Work Experiences Yet</h4>
                            <p class="text-muted">Start by adding your first work experience entry.</p>
                            <a href="{{ route('admin.work-experiences.create') }}" class="btn btn-primary-custom">
                                <i class="fas fa-plus me-2"></i>Add Work Experience
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .page-header {
        background: var(--dark-secondary);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .page-title {
        color: var(--text-primary);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        margin: 0;
    }

    .card {
        background: var(--dark-secondary);
        border: 1px solid var(--border-color);
        border-radius: 15px;
    }

    .card-header {
        background: var(--dark-tertiary);
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    .card-title {
        color: var(--text-primary);
        margin: 0;
    }

    .table {
        color: var(--text-primary);
    }

    .table th {
        background: var(--dark-tertiary);
        border-color: var(--border-color);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1rem;
    }

    .table td {
        border-color: var(--border-color);
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: var(--dark-tertiary);
    }

    .company-logo-small {
        width: 32px;
        height: 32px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    .company-logo-placeholder-small {
        width: 32px;
        height: 32px;
        background: var(--dark-tertiary);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .employment-type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
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

    .duration-info {
        display: flex;
        flex-direction: column;
    }

    .duration-text {
        font-weight: 600;
        color: var(--text-primary);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
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

    @keyframes pulse {
        0% { box-shadow: 0 0 10px currentColor; }
        50% { box-shadow: 0 0 20px currentColor, 0 0 30px currentColor; }
        100% { box-shadow: 0 0 10px currentColor; }
    }

    .btn-group .btn {
        border-radius: 6px;
    }

    .btn-group .btn:not(:last-child) {
        margin-right: 0.25rem;
    }

    .btn-outline-primary {
        border-color: var(--accent-primary);
        color: var(--accent-primary);
    }

    .btn-outline-primary:hover {
        background: var(--accent-primary);
        border-color: var(--accent-primary);
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    .btn-outline-danger:hover {
        background: #dc3545;
        border-color: #dc3545;
    }

    .badge {
        font-size: 0.75rem;
    }

    .badge.bg-success {
        background: rgba(0, 255, 136, 0.1) !important;
        color: var(--neon-green) !important;
        border: 1px solid var(--neon-green);
    }

    .badge.bg-secondary {
        background: rgba(136, 136, 136, 0.1) !important;
        color: var(--neon-gray) !important;
        border: 1px solid var(--neon-gray);
    }
</style>
@endpush
@endsection
