@extends('layouts.app')

@section('title', 'User Management - Admin Panel')

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

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .role-admin {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .role-viewer {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-inactive {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    .btn-action {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-toggle {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border: none;
    }

    .btn-toggle:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        border: none;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
    }

    .table-dark {
        background: rgba(26, 26, 26, 0.8);
        color: white;
    }

    .table-dark th {
        border-color: rgba(139, 92, 246, 0.2);
        background: rgba(139, 92, 246, 0.1);
        color: white;
    }

    .table-dark td {
        border-color: rgba(139, 92, 246, 0.1);
        color: white;
    }

    .pagination .page-link {
        background: rgba(45, 45, 45, 0.8);
        border-color: rgba(139, 92, 246, 0.2);
        color: white;
    }

    .pagination .page-link:hover {
        background: rgba(139, 92, 246, 0.2);
        border-color: rgba(139, 92, 246, 0.3);
        color: white;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        border-color: #8b5cf6;
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="admin-card p-4">
                    <!-- Header -->
                    <div class="admin-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1">
                                    <i class="fas fa-users me-2"></i>
                                    User Management
                                </h2>
                                <p class="mb-0 opacity-75">Manage admin and viewer accounts</p>
                            </div>
                            <a href="{{ url(route('admin.users.create'), [], true) }}" class="btn btn-light">
                                <i class="fas fa-plus me-1"></i>
                                Add New User
                            </a>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-dark border-primary">
                                <div class="card-body text-center">
                                    <h5 class="text-primary mb-1">{{ $users->total() }}</h5>
                                    <small class="text-white-50">Total Users</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-dark border-success">
                                <div class="card-body text-center">
                                    <h5 class="text-success mb-1">{{ $users->where('role', 'admin')->count() }}</h5>
                                    <small class="text-white-50">Admins</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-dark border-info">
                                <div class="card-body text-center">
                                    <h5 class="text-info mb-1">{{ $users->where('role', 'viewer')->count() }}</h5>
                                    <small class="text-white-50">Viewers</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-dark border-warning">
                                <div class="card-body text-center">
                                    <h5 class="text-warning mb-1">{{ $users->where('is_active', true)->count() }}</h5>
                                    <small class="text-white-50">Active Users</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-white">{{ $user->name }}</div>
                                                    @if($user->id === auth()->id())
                                                        <small class="text-warning">(You)</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-white">{{ $user->email }}</td>
                                        <td>
                                            <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-viewer' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-white-50">{{ $user->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($user->id !== auth()->id())
                                                    <form method="POST" action="{{ secure_url(route('admin.users.toggle-status', $user)) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-toggle btn-action"
                                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                                            <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ url(route('admin.users.edit', $user), [], true) }}" 
                                                   class="btn btn-edit btn-action"
                                                   title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($user->id !== auth()->id() && !($user->role === 'admin' && $users->where('role', 'admin')->count() <= 1))
                                                    <form method="POST" action="{{ secure_url(route('admin.users.delete', $user)) }}" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-delete btn-action"
                                                                title="Delete User">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-white-50 py-4">
                                            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">No users found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
