@extends('layouts.app')

@section('title', 'Admin Login - Jawahar Ganesh @ Jay')

@push('styles')
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Admin Login Header -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%231a1a1a' rx='8'/%3E%3Crect x='8' y='8' width='84' height='84' fill='none' stroke='%238b5cf6' stroke-width='2' rx='6'/%3E%3Ctext x='50' y='42' font-family='Courier New, monospace' font-size='20' font-weight='bold' text-anchor='middle' fill='%23ffffff'%3EJXG%3C/text%3E%3Ctext x='50' y='62' font-family='Courier New, monospace' font-size='12' text-anchor='middle' fill='%238b5cf6'%3E%3E_%3C/text%3E%3C/svg%3E"
                                    alt="JXG Terminal Logo" class="mb-3" style="width: 60px; height: 60px;">
                            </div>
                            <h2 class="h4 text-white mb-2">Admin Portal</h2>
                            <p class="text-muted">Access your portfolio management dashboard</p>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ secure_url('/admin/login') }}" data-secure="true" onsubmit="return submitSecurely(this)">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label text-white">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="admin@portfolio.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-white">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required placeholder="Enter your password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="remember">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Sign In
                            </button>

                            <!-- Back to Portfolio -->
                            <div class="text-center">
                                <a href="{{ url('/', [], true) }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Back to Portfolio
                                </a>
                            </div>
                        </form>

                        <!-- Demo Credentials -->
                        <div class="mt-4 p-3 bg-dark border border-secondary rounded">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Demo Credentials
                            </h6>
                            <small class="text-white">
                                <strong>Email:</strong> demo@portfolio.com<br>
                                <strong>Password:</strong> demo1234
                            </small>
                        </div>

                        <!-- Security Notice -->
                        <div class="mt-3 p-3 bg-gradient-danger border border-danger rounded">
                            <h6 class="text-white mb-2">
                                <i class="fas fa-shield-alt me-1"></i>
                                Security Features
                            </h6>
                            <small class="text-white">
                                • Session-based authentication<br>
                                • Password hashing with bcrypt<br>
                                • CSRF protection enabled<br>
                                • Database encryption<br>
                                • Admin access logging
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
            }

            .form-control {
                background-color: rgba(42, 42, 42, 0.8);
                border: 1px solid #333;
                color: #ffffff;
            }

            .form-control:focus {
                background-color: rgba(42, 42, 42, 0.9);
                border-color: #8b5cf6;
                color: #ffffff;
                box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
            }

            .form-control::placeholder {
                color: #666;
            }

            .btn-primary {
                background: linear-gradient(135deg, #8b5cf6, #a855f7);
                border: none;
                padding: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #7c3aed, #9333ea);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
            }

            .form-check-input:checked {
                background-color: #8b5cf6;
                border-color: #8b5cf6;
            }

            .invalid-feedback {
                color: #ff6b6b;
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #ef4444, #dc2626) !important;
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
@endsection
