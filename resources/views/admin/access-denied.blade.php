@extends('layouts.app')

@section('title', 'Access Denied - Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <!-- Access Denied Icon -->
                    <div class="mb-4">
                        <i class="fas fa-shield-alt text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <!-- Error Message -->
                    <h2 class="h4 text-white mb-3">Access Denied</h2>
                    <p class="text-white mb-4">
                        You don't have permission to access this area. 
                        Admin privileges are required.
                    </p>
                    
                    <!-- Security Notice -->
                    <div class="alert alert-danger border-danger mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Security Notice:</strong> Unauthorized access attempts are logged and monitored.
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Login as Admin
                        </a>
                        <a href="{{ url('/', [], true) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>
                            Back to Portfolio
                        </a>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="mt-4 pt-3 border-top border-secondary">
                        <small class="text-white">
                            <i class="fas fa-info-circle me-1"></i>
                            If you believe this is an error, please contact the administrator.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .min-vh-100 {
        min-height: 100vh;
    }

    .card {
        background: rgba(26, 26, 26, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
        border: none;
        padding: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed, #9333ea);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    .btn-outline-secondary {
        border: 1px solid #6b7280;
        color: #6b7280;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6b7280;
        color: white;
        transform: translateY(-2px);
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
    }

    .text-danger {
        color: #ef4444 !important;
    }
</style>
@endpush
@endsection
