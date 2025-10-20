@extends('layouts.app')

@section('title', 'Admin Dashboard - Jawahar Ganesh @ Jay')

@section('content')
    <style>
        /* Custom dark admin dashboard styling */
        .bg-gradient-primary {
            background: #1a1a1a !important;
            border: 1px solid #8b5cf6 !important;
        }

        .bg-gradient-primary h4,
        .bg-gradient-primary p {
            color: #8b5cf6 !important;
        }

        .bg-gradient-primary i {
            color: #8b5cf6 !important;
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

        .bg-gradient-dark {
            background: #1a1a1a !important;
            border: 1px solid #6b7280 !important;
        }

        .bg-gradient-dark h4,
        .bg-gradient-dark p {
            color: #6b7280 !important;
        }

        .bg-gradient-dark i {
            color: #6b7280 !important;
        }

        .bg-gradient-secondary {
            background: #1a1a1a !important;
            border: 1px solid #6b7280 !important;
        }

        .bg-gradient-secondary h4,
        .bg-gradient-secondary p {
            color: #6b7280 !important;
        }

        .bg-gradient-secondary i {
            color: #6b7280 !important;
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

        /* Quick Actions Button Styling */
        .btn-primary {
            background: #1a1a1a !important;
            border: 1px solid #3b82f6 !important;
            color: #3b82f6 !important;
        }

        .btn-primary:hover {
            background: #2a2a2a !important;
            border-color: #3b82f6 !important;
            color: #3b82f6 !important;
        }

        .btn-success {
            background: #1a1a1a !important;
            border: 1px solid #10b981 !important;
            color: #10b981 !important;
        }

        .btn-success:hover {
            background: #2a2a2a !important;
            border-color: #10b981 !important;
            color: #10b981 !important;
        }

        .btn-info {
            background: #1a1a1a !important;
            border: 1px solid #06b6d4 !important;
            color: #06b6d4 !important;
        }

        .btn-info:hover {
            background: #2a2a2a !important;
            border-color: #06b6d4 !important;
            color: #06b6d4 !important;
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

        .btn-warning {
            background: #1a1a1a !important;
            border: 1px solid #f59e0b !important;
            color: #f59e0b !important;
        }

        .btn-warning:hover {
            background: #2a2a2a !important;
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
    </style>
    <div class="container-fluid" style="padding-top: 100px; padding-bottom: 2rem;">
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 text-white mb-1">Admin Dashboard</h1>
                        <p class="mb-0">
                            <span id="welcomeText" style="color: #ff8c00; text-shadow: 0 0 8px #ff8c00;"></span>
                            <span class="typing-cursor" style="color: #ff8c00; animation: blink 1s infinite;">|</span>
                        </p>
                    </div>
                    <div class="d-flex gap-2 mt-3 mt-md-0">
                        <form method="POST" action="{{ secure_url('/admin/logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Overview Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-briefcase me-2"></i>
                            Portfolio Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-gradient-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ $stats['total_portfolios'] }}</h4>
                                                <p class="mb-0">Total Portfolios</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-briefcase fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-gradient-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ $stats['published_portfolios'] }}</h4>
                                                <p class="mb-0">Published</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-gradient-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ $stats['featured_portfolios'] }}</h4>
                                                <p class="mb-0">Featured</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-star fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics & Communication Section -->
        <div class="row mb-5">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Website Analytics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-gradient-dark text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ number_format($stats['total_visitors']) }}</h4>
                                                <p class="mb-0">Total Visitors</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-users fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-gradient-secondary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ number_format($stats['total_page_views']) }}</h4>
                                                <p class="mb-0">Page Views</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-eye fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-gradient-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ number_format($stats['today_visitors']) }}</h4>
                                                <p class="mb-0">Today's Visitors</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Additional Metrics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-gradient-secondary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ $stats['contact_stats']['unread_percentage'] }}%</h4>
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
                    </div>
                </div>
            </div>
        </div>


        <!-- Quick Actions & Management -->
        <div class="row mb-5">
            <!-- Portfolio Management -->
            @if (auth()->user()->canManagePortfolios())
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-briefcase me-2"></i>
                                Portfolio Management
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.portfolios.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Add New Portfolio
                                </a>
                                <a href="{{ route('admin.portfolios') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i>
                                    Manage Portfolios
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Work Experience Management -->
            @if (auth()->user()->canManagePortfolios())
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user-tie me-2"></i>
                                Work Experience Management
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.work-experiences.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>
                                    Add Work Experience
                                </a>
                                <a href="{{ route('admin.work-experiences') }}" class="btn btn-outline-success">
                                    <i class="fas fa-list me-2"></i>
                                    Manage Experiences
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Analytics & Monitoring -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Analytics & Monitoring
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.visitors') }}" class="btn btn-info">
                                <i class="fas fa-chart-line me-2"></i>
                                Visitor Analytics
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-light" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>
                                View Live Portfolio
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Communication & Users -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-comments me-2"></i>
                            Communication & Users
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Total Contacts Card -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card bg-gradient-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ $stats['total_contacts'] }}</h4>
                                                <p class="mb-0">Total Contacts</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-envelope fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if (auth()->user()->canManageContacts())
                                <a href="{{ route('admin.contacts') }}" class="btn btn-success">
                                    <i class="fas fa-envelope me-2"></i>
                                    View Messages ({{ $stats['unread_contacts'] }} unread)
                                </a>
                            @endif
                            @if (auth()->user()->canManageUsers())
                                <a href="{{ url(route('admin.users'), [], true) }}" class="btn btn-warning">
                                    <i class="fas fa-users me-2"></i>
                                    Manage Users
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            @if (auth()->user()->canManageContacts())
                <!-- Recent Contacts -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-envelope me-2"></i>
                                Recent Messages
                            </h5>
                            <a href="{{ route('admin.contacts') }}" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            @forelse($recentContacts as $contact)
                                <div class="d-flex align-items-center mb-3 p-3 border border-secondary rounded">
                                    <div class="flex-shrink-0">
                                        <div class="bg-{{ $contact->is_read ? 'secondary' : 'primary' }} rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-white">{{ $contact->name }}</h6>
                                        <p class="mb-1 text-muted small">{{ Str::limit($contact->message, 60) }}</p>
                                        <small style="color: #ffff00; text-shadow: 0 0 5px #ffff00;">
                                            {{ $contact->created_at->diffForHumans() }}
                                            @if (!$contact->is_read)
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('admin.contacts.show', $contact) }}"
                                            class="btn btn-sm btn-outline-light">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No messages yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->user()->canManagePortfolios())
                <!-- Recent Portfolios -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">
                                <i class="fas fa-briefcase me-2"></i>
                                Recent Portfolios
                            </h5>
                            <a href="{{ route('admin.portfolios') }}" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            @forelse($recentPortfolios as $portfolio)
                                <div class="d-flex align-items-center mb-3 p-3 border border-secondary rounded">
                                    <div class="flex-shrink-0">
                                        <div class="bg-{{ $portfolio->is_published ? 'success' : 'warning' }} rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-briefcase text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-white">{{ $portfolio->title }}</h6>
                                        <p class="mb-1 text-white small">
                                            {{ Str::limit($portfolio->short_description, 60) }}</p>
                                        <small style="color: #ffff00; text-shadow: 0 0 5px #ffff00;">
                                            {{ $portfolio->created_at->diffForHumans() }}
                                            @if ($portfolio->is_featured)
                                                <span class="badge bg-warning ms-2">Featured</span>
                                            @endif
                                            @if (!$portfolio->is_published)
                                                <span class="badge bg-secondary ms-2">Draft</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('admin.portfolios.edit', $portfolio) }}"
                                            class="btn btn-sm btn-outline-light">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No portfolios yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <!-- View-only Portfolio Section for Viewers -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="fas fa-eye me-2"></i>
                                Portfolio Overview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-4">
                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                <p class="text-white">You have view-only access to the portfolio system.</p>
                                <a href="{{ route('portfolio') }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    View Public Portfolio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .bg-gradient-primary {
                background: linear-gradient(135deg, #8b5cf6, #a855f7) !important;
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, #10b981, #059669) !important;
            }

            .bg-gradient-warning {
                background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            }

            .bg-gradient-info {
                background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            }

            .bg-gradient-secondary {
                background: linear-gradient(135deg, #6b7280, #4b5563) !important;
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

            @keyframes blink {

                0%,
                50% {
                    opacity: 1;
                }

                51%,
                100% {
                    opacity: 0;
                }
            }

            /* Ensure proper spacing from fixed navbar */
            .container-fluid {
                margin-top: 0 !important;
                padding-top: 100px !important;
            }

            /* Responsive button spacing */
            @media (max-width: 768px) {
                .d-flex.gap-2 {
                    flex-direction: column;
                    gap: 0.5rem !important;
                }

                .btn {
                    width: 100%;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const welcomeText = document.getElementById('welcomeText');
                const user = '{{ Auth::user()->name }}';
                const message = `Welcome back, ${user}!`;

                let index = 0;

                function typeWelcome() {
                    if (index < message.length) {
                        welcomeText.textContent += message[index];
                        index++;
                        setTimeout(typeWelcome, 100); // 100ms delay between characters
                    }
                }

                // Start typing after a short delay
                setTimeout(typeWelcome, 500);
            });
        </script>
    @endpush
@endsection
