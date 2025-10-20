@extends('layouts.app')

@section('title', 'View Message - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-white mb-1">Message Details</h1>
                    <p class="text-muted mb-0">View and manage contact form submission</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.contacts') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Messages
                    </a>
                    @if(!$contact->is_read)
                        <form method="POST" action="{{ secure_url(route('admin.contacts.mark-read', $contact)) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i>
                                Mark as Read
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ secure_url(route('admin.contacts.delete', $contact)) }}" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Message Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        Message Content
                    </h5>
                    <div>
                        @if($contact->is_read)
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Read
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-envelope me-1"></i>Unread
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="message-content">
                        {{ $contact->message }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="mailto:{{ $contact->email }}?subject=Re: Your Message" class="btn btn-primary w-100">
                                <i class="fas fa-reply me-2"></i>
                                Reply via Email
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            @if($contact->phone)
                                <a href="tel:{{ $contact->phone }}" class="btn btn-success w-100">
                                    <i class="fas fa-phone me-2"></i>
                                    Call {{ $contact->name }}
                                </a>
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-phone me-2"></i>
                                    No Phone Number
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Contact Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="contact-info">
                        <!-- Name -->
                        <div class="info-item mb-3">
                            <label class="info-label">Name</label>
                            <div class="info-value">{{ $contact->name }}</div>
                        </div>

                        <!-- Email -->
                        <div class="info-item mb-3">
                            <label class="info-label">Email</label>
                            <div class="info-value">
                                <a href="mailto:{{ $contact->email }}" class="text-primary">
                                    {{ $contact->email }}
                                </a>
                            </div>
                        </div>

                        <!-- Organization -->
                        @if($contact->organization)
                            <div class="info-item mb-3">
                                <label class="info-label">Organization</label>
                                <div class="info-value">{{ $contact->organization }}</div>
                            </div>
                        @endif

                        <!-- University -->
                        @if($contact->university)
                            <div class="info-item mb-3">
                                <label class="info-label">University</label>
                                <div class="info-value">{{ $contact->university }}</div>
                            </div>
                        @endif

                        <!-- Phone -->
                        @if($contact->phone)
                            <div class="info-item mb-3">
                                <label class="info-label">Phone</label>
                                <div class="info-value">
                                    <a href="tel:{{ $contact->phone }}" class="text-success">
                                        {{ $contact->phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Message Metadata -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Message Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="metadata">
                        <!-- Sent Date -->
                        <div class="meta-item mb-3">
                            <label class="meta-label">Sent</label>
                            <div class="meta-value">
                                {{ $contact->created_at->format('F d, Y') }}<br>
                                <small class="text-muted">{{ $contact->created_at->format('h:i A') }}</small>
                            </div>
                        </div>

                        <!-- Read Status -->
                        <div class="meta-item mb-3">
                            <label class="meta-label">Status</label>
                            <div class="meta-value">
                                @if($contact->is_read)
                                    <span class="badge bg-success">Read</span><br>
                                    <small class="text-muted">
                                        Read on {{ $contact->read_at->format('M d, Y h:i A') }}
                                    </small>
                                @else
                                    <span class="badge bg-danger">Unread</span>
                                @endif
                            </div>
                        </div>

                        <!-- Message Length -->
                        <div class="meta-item">
                            <label class="meta-label">Message Length</label>
                            <div class="meta-value">{{ strlen($contact->message) }} characters</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        background: rgba(26, 26, 26, 0.95);
        border: 1px solid #333;
        backdrop-filter: blur(10px);
    }
    
    .card-header {
        background: rgba(42, 42, 42, 0.8);
        border-bottom: 1px solid #333;
    }
    
    .message-content {
        background: rgba(42, 42, 42, 0.5);
        border: 1px solid #333;
        border-radius: 8px;
        padding: 1.5rem;
        color: #ffffff;
        line-height: 1.6;
        white-space: pre-wrap;
        font-size: 1rem;
    }
    
    .info-item, .meta-item {
        border-bottom: 1px solid #333;
        padding-bottom: 0.75rem;
    }
    
    .info-item:last-child, .meta-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0 !important;
    }
    
    .info-label, .meta-label {
        color: #8b5cf6;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .info-value, .meta-value {
        color: #ffffff;
        font-size: 1rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed, #9333ea);
        transform: translateY(-1px);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush
@endsection
