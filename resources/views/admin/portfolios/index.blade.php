@extends('layouts.app')

@section('title', 'Portfolio Management - Admin')

@section('content')
<div class="container-fluid" style="padding-top: 100px; padding-bottom: 2rem;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-white mb-1">
                        <i class="fas fa-briefcase me-2"></i>
                        Portfolio Management
                    </h1>
                    <p class="text-muted mb-0">Manage your portfolio projects</p>
                </div>
                <div class="d-flex gap-2 mt-3 mt-md-0">
                    <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Add New Portfolio
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">
                        <i class="fas fa-home me-1"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolios Table -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-list me-2"></i>
                        All Portfolios ({{ $portfolios->count() }})
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success">{{ $portfolios->where('is_published', true)->count() }} Published</span>
                        <span class="badge bg-warning">{{ $portfolios->where('is_featured', true)->count() }} Featured</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($portfolios->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-white">Project</th>
                                        <th class="text-white">Category</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">Duration</th>
                                        <th class="text-white">Created</th>
                                        <th class="text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($portfolios as $portfolio)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($portfolio->images && count($portfolio->images) > 0)
                                                        <img src="{{ $portfolio->images[0] }}" 
                                                             alt="{{ $portfolio->title }}" 
                                                             class="rounded me-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 text-white">{{ $portfolio->title }}</h6>
                                                        <small class="text-muted">{{ Str::limit($portfolio->short_description, 50) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $portfolio->category }}</span>
                                            </td>
                                            <td>
                                                @if($portfolio->is_published)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                                @if($portfolio->is_featured)
                                                    <span class="badge bg-warning ms-1">Featured</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($portfolio->duration_months)
                                                    <span class="text-white">{{ $portfolio->duration_months }} month(s)</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span style="color: #ffff00; text-shadow: 0 0 5px #ffff00;">
                                                    {{ $portfolio->created_at->diffForHumans() }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.portfolios.edit', $portfolio) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('portfolio.item', $portfolio->slug) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="View" 
                                                       target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" 
                                                          action="{{ secure_url(route('admin.portfolios.delete', $portfolio)) }}" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this portfolio? This action cannot be undone.')">
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
                            <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                            <h5 class="text-white mb-3">No Portfolios Found</h5>
                            <p class="text-muted mb-4">Get started by creating your first portfolio project.</p>
                            <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Create First Portfolio
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .card-header {
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
    }

    .table-dark {
        --bs-table-bg: rgba(26, 26, 26, 0.8);
        --bs-table-striped-bg: rgba(42, 42, 42, 0.5);
        --bs-table-hover-bg: rgba(139, 92, 246, 0.1);
    }

    .table th {
        border-color: rgba(139, 92, 246, 0.2);
        font-weight: 600;
    }

    .table td {
        border-color: rgba(139, 92, 246, 0.1);
        vertical-align: middle;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--accent-secondary), var(--accent-primary));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .alert {
        border: none;
        border-radius: 8px;
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .badge {
        font-size: 0.75rem;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }
</style>
@endpush
