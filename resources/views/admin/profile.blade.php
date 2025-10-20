@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-white">
                        <i class="fas fa-user-cog me-2"></i>
                        Admin Profile
                    </h1>
                    <p class="text-muted mb-0">Manage your admin account settings</p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card bg-dark border-secondary">
                        <div class="card-header bg-secondary">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-user me-2"></i>
                                Profile Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ secure_url(route('admin.profile')) }}" data-secure="true" onsubmit="return submitSecurely(this)">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label text-white">
                                            <i class="fas fa-user me-1"></i>
                                            Full Name
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label text-white">
                                            <i class="fas fa-envelope me-1"></i>
                                            Email Address
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4 border-secondary">

                                <h6 class="text-white mb-3">
                                    <i class="fas fa-lock me-2"></i>
                                    Change Password
                                </h6>

                                <div class="mb-3">
                                    <label for="current_password" class="form-label text-white">
                                        <i class="fas fa-key me-1"></i>
                                        Current Password
                                    </label>
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label text-white">
                                            <i class="fas fa-lock me-1"></i>
                                            New Password
                                        </label>
                                        <input type="password" 
                                               class="form-control @error('new_password') is-invalid @enderror" 
                                               id="new_password" 
                                               name="new_password">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="new_password_confirmation" class="form-label text-white">
                                            <i class="fas fa-lock me-1"></i>
                                            Confirm New Password
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="new_password_confirmation" 
                                               name="new_password_confirmation">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Profile Info Sidebar -->
                <div class="col-lg-4">
                    <div class="card bg-dark border-secondary">
                        <div class="card-header bg-secondary">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-info-circle me-2"></i>
                                Account Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Account Created</label>
                                <p class="text-white mb-0">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $user->created_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Last Updated</label>
                                <p class="text-white mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $user->updated_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">User ID</label>
                                <p class="text-white mb-0">
                                    <i class="fas fa-hashtag me-1"></i>
                                    #{{ $user->id }}
                                </p>
                            </div>

                            <hr class="border-secondary">

                            <div class="d-grid">
                                <a href="{{ url('/admin/logout', [], true) }}" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Are you sure you want to logout?')">
                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tips -->
                    <div class="card bg-dark border-warning mt-3">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0 text-dark">
                                <i class="fas fa-shield-alt me-2"></i>
                                Security Tips
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2 text-white">
                                    <i class="fas fa-check text-success me-1"></i>
                                    Use a strong, unique password
                                </li>
                                <li class="mb-2 text-white">
                                    <i class="fas fa-check text-success me-1"></i>
                                    Change password regularly
                                </li>
                                <li class="mb-2 text-white">
                                    <i class="fas fa-check text-success me-1"></i>
                                    Keep your email address updated
                                </li>
                                <li class="mb-0 text-white">
                                    <i class="fas fa-check text-success me-1"></i>
                                    Logout when finished
                                </li>
                            </ul>
                        </div>
                    </div>
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

    .form-control {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
    }

    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.08);
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        color: white;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
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

    .alert {
        border: none;
        border-radius: 8px;
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
// Force HTTPS form submission
function submitSecurely(form) {
    // Ensure the form action uses HTTPS
    const currentUrl = window.location.href;
    if (currentUrl.startsWith('https://')) {
        form.action = form.action.replace('http://', 'https://');
    }
    return true;
}
</script>
@endpush
