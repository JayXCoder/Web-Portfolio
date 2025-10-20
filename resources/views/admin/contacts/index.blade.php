@extends('layouts.app')

@section('title', 'Contact Management - Admin')

@section('content')
    <style>
        /* Contact page dark mode styling */
        .text-muted {
            color: #ffffff !important;
        }

        .card {
            background: #1a1a1a !important;
            border: 1px solid #333333 !important;
            color: #ffffff;
        }

        .card-header {
            background: #2a2a2a !important;
            border-bottom: 1px solid #333333 !important;
            color: #ffffff !important;
        }

        /* Statistics Cards - Black with colored text */
        .bg-gradient-info {
            background: #1a1a1a !important;
            border: 1px solid #3b82f6 !important;
        }

        .bg-gradient-info h4,
        .bg-gradient-info p {
            color: #3b82f6 !important;
        }

        .bg-gradient-info i {
            color: #3b82f6 !important;
        }

        .bg-gradient-danger {
            background: #1a1a1a !important;
            border: 1px solid #ef4444 !important;
        }

        .bg-gradient-danger h4,
        .bg-gradient-danger p {
            color: #ef4444 !important;
        }

        .bg-gradient-danger i {
            color: #ef4444 !important;
        }

        .bg-gradient-success {
            background: #1a1a1a !important;
            border: 1px solid #10b981 !important;
        }

        .bg-gradient-success h4,
        .bg-gradient-success p {
            color: #10b981 !important;
        }

        .bg-gradient-success i {
            color: #10b981 !important;
        }

        .bg-gradient-warning {
            background: #1a1a1a !important;
            border: 1px solid #f59e0b !important;
        }

        .bg-gradient-warning h4,
        .bg-gradient-warning p {
            color: #f59e0b !important;
        }

        .bg-gradient-warning i {
            color: #f59e0b !important;
        }

        /* Table styling */
        .table-dark {
            background: #1a1a1a !important;
            color: #ffffff !important;
        }

        .table-dark th {
            background: #2a2a2a !important;
            border-color: #333333 !important;
            color: #ffffff !important;
        }

        .table-dark td {
            background: #1a1a1a !important;
            border-color: #333333 !important;
            color: #ffffff !important;
        }

        .table-dark tbody tr {
            background: #1a1a1a !important;
        }

        .table-dark tbody tr:hover {
            background: #2a2a2a !important;
        }

        .table-warning {
            background: rgba(245, 158, 11, 0.1) !important;
        }

        /* Button styling */
        .btn-outline-light {
            background: #1a1a1a !important;
            border: 1px solid #ffffff !important;
            color: #ffffff !important;
        }

        .btn-outline-light:hover {
            background: #2a2a2a !important;
            border-color: #ffffff !important;
            color: #ffffff !important;
        }

        .btn-outline-primary {
            background: #1a1a1a !important;
            border: 1px solid #3b82f6 !important;
            color: #3b82f6 !important;
        }

        .btn-outline-primary:hover {
            background: #2a2a2a !important;
            border-color: #3b82f6 !important;
            color: #3b82f6 !important;
        }

        .btn-outline-danger {
            background: #1a1a1a !important;
            border: 1px solid #ef4444 !important;
            color: #ef4444 !important;
        }

        .btn-outline-danger:hover {
            background: #2a2a2a !important;
            border-color: #ef4444 !important;
            color: #ef4444 !important;
        }
    </style>
    <div class="container-fluid" style="padding-top: 100px; padding-bottom: 2rem;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 text-white mb-1">Contact Management</h1>
                        <p class="text-muted mb-0">Manage incoming messages and inquiries</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-gradient-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ $stats['total'] }}</h4>
                                <p class="mb-0">Total Messages</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-envelope fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-gradient-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ $stats['unread'] }}</h4>
                                <p class="mb-0">Unread</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-envelope-open fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-gradient-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ $stats['read'] }}</h4>
                                <p class="mb-0">Read</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-gradient-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ $stats['unread_percentage'] }}%</h4>
                                <p class="mb-0">Unread Rate</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-pie fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contacts Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    All Messages
                </h5>
            </div>
            <div class="card-body">
                @if ($contacts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Institution</th>
                                    <th>Phone</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $contact)
                                    <tr class="{{ $contact->is_read ? '' : 'table-warning' }}">
                                        <td>
                                            @if ($contact->is_read)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Read
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-envelope me-1"></i>New
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $contact->name }}</strong>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $contact->email }}" class="text-primary">
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $contact->institution ?? 'Not provided' }}
                                        </td>
                                        <td>
                                            @if ($contact->phone)
                                                <a href="tel:{{ $contact->phone }}" class="text-success">
                                                    {{ $contact->phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;"
                                                title="{{ $contact->message }}">
                                                {{ Str::limit($contact->message, 50) }}
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $contact->created_at->format('M d, Y') }}<br>
                                                {{ $contact->created_at->format('h:i A') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                                    class="btn btn-outline-primary" title="View Message">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if (!$contact->is_read)
                                                    <form method="POST"
                                                        action="{{ secure_url(route('admin.contacts.mark-read', $contact)) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-outline-success"
                                                            title="Mark as Read">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form method="POST"
                                                    action="{{ secure_url(route('admin.contacts.delete', $contact)) }}"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this message?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                        title="Delete Message">
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
                        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No Messages Yet</h4>
                        <p class="text-muted">Contact form submissions will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-gradient-info {
                background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, #10b981, #059669) !important;
            }

            .bg-gradient-warning {
                background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            }

            .card {
                background: rgba(26, 26, 26, 0.95);
                border: 1px solid #333;
                backdrop-filter: blur(10px);
            }

            .card-header {
                background: rgba(42, 42, 42, 0.8);
                border-bottom: 1px solid #333;
            }

            .table-dark {
                --bs-table-bg: rgba(26, 26, 26, 0.8);
                --bs-table-striped-bg: rgba(42, 42, 42, 0.5);
                --bs-table-hover-bg: rgba(42, 42, 42, 0.8);
                --bs-table-border-color: #333;
            }

            .table-warning {
                --bs-table-accent-bg: rgba(245, 158, 11, 0.1);
            }

            .text-truncate {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        </style>
    @endpush
@endsection
