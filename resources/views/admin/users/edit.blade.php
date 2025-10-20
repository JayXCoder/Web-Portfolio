@extends('layouts.app')

@section('title', 'Edit User - Admin Panel')

@push('styles')
<style>
    .admin-container {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        min-height: 100vh;
        padding-top: 100px;
    }

    .admin-card {
        background: rgba(45, 45, 45, 0.95);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(139, 92, 246, 0.1);
    }

    .admin-header {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
        margin: -1rem -1rem 2rem -1rem;
    }

    .form-control {
        background: rgba(26, 26, 26, 0.8);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: white;
        border-radius: 8px;
    }

    .form-control:focus {
        background: rgba(26, 26, 26, 0.9);
        border-color: #8b5cf6;
        color: white;
        box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-check-input:checked {
        background-color: #8b5cf6;
        border-color: #8b5cf6;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    .btn-secondary {
        background: rgba(107, 114, 128, 0.8);
        border: 1px solid rgba(107, 114, 128, 0.3);
        color: white;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: rgba(107, 114, 128, 1);
        color: white;
    }

    .role-card {
        background: rgba(26, 26, 26, 0.6);
        border: 2px solid rgba(139, 92, 246, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .role-card:hover {
        border-color: rgba(139, 92, 246, 0.5);
        background: rgba(139, 92, 246, 0.1);
    }

    .role-card.selected {
        border-color: #8b5cf6;
        background: rgba(139, 92, 246, 0.2);
    }

    .role-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }

    .role-admin .role-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .role-viewer .role-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
        border-radius: 8px;
    }

    .invalid-feedback {
        color: #fca5a5;
        font-size: 0.875rem;
    }

    .user-info-card {
        background: rgba(26, 26, 26, 0.6);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 2rem;
        margin: 0 auto 1rem;
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="admin-card p-4">
                    <!-- Header -->
                    <div class="admin-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit User
                                </h2>
                                <p class="mb-0 opacity-75">Update user information and permissions</p>
                            </div>
                            <a href="{{ url(route('admin.users'), [], true) }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Users
                            </a>
                        </div>
                    </div>

                    <!-- User Info Card -->
                    <div class="user-info-card">
                        <div class="text-center">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <h4 class="text-white mb-1">{{ $user->name }}</h4>
                            <p class="text-white-50 mb-0">{{ $user->email }}</p>
                            <div class="mt-2">
                                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }} me-2">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ secure_url(route('admin.users.update', $user)) }}" enctype="multipart/form-data" data-secure="true" onsubmit="return submitSecurely(this)">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
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
                                       placeholder="Enter full name" 
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
                                       placeholder="Enter email address" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password (Optional) -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label text-white">
                                    <i class="fas fa-lock me-1"></i>
                                    New Password
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Leave blank to keep current password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-white-50">Leave blank to keep current password</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label text-white">
                                    <i class="fas fa-lock me-1"></i>
                                    Confirm New Password
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirm new password">
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="form-label text-white mb-3">
                                <i class="fas fa-user-tag me-1"></i>
                                User Role
                            </label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="role-card role-admin" onclick="selectRole('admin')">
                                        <div class="text-center">
                                            <div class="role-icon">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                            <h5 class="text-white mb-2">Admin</h5>
                                            <p class="text-white-50 small mb-3">
                                                Full access to all features including portfolio management, 
                                                contact management, and user management.
                                            </p>
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="role" 
                                                       id="role_admin" 
                                                       value="admin" 
                                                       {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                                                <label class="form-check-label text-white ms-2" for="role_admin">
                                                    Admin Access
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="role-card role-viewer" onclick="selectRole('viewer')">
                                        <div class="text-center">
                                            <div class="role-icon">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                            <h5 class="text-white mb-2">Viewer</h5>
                                            <p class="text-white-50 small mb-3">
                                                Limited access to view portfolios and dashboard. 
                                                Cannot edit content or manage users.
                                            </p>
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="role" 
                                                       id="role_viewer" 
                                                       value="viewer" 
                                                       {{ old('role', $user->role) === 'viewer' ? 'checked' : '' }}>
                                                <label class="form-check-label text-white ms-2" for="role_viewer">
                                                    Viewer Access
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="is_active">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Account is active
                                </label>
                                <small class="d-block text-white-50 mt-1">
                                    Inactive accounts cannot log in to the system.
                                </small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ url(route('admin.users'), [], true) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectRole(role) {
    // Remove selected class from all cards
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    event.currentTarget.classList.add('selected');
    
    // Check the corresponding radio button
    document.getElementById('role_' + role).checked = true;
}

// Initialize selected role on page load
document.addEventListener('DOMContentLoaded', function() {
    const checkedRole = document.querySelector('input[name="role"]:checked');
    if (checkedRole) {
        const roleCard = checkedRole.closest('.role-card');
        if (roleCard) {
            roleCard.classList.add('selected');
        }
    }
});

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
@endsection
