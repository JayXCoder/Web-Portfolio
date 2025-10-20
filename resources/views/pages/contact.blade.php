@extends('layouts.app')

@section('title', 'Contact - Jawahar Ganesh @ Jay')

@section('content')
<section class="section" style="padding-top: 150px; background: var(--dark-secondary);">
    <div class="container">
        <h2 class="section-title">Get In Touch</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-info">
                    <h3 class="mb-4">Let's Connect</h3>
                    <p class="mb-4">
                        I'm always interested in new opportunities, collaborations, and discussions 
                        about AI, cybersecurity, full-stack development, and innovative tech solutions. 
                        Whether you want to discuss a project, share ideas, or collaborate on something 
                        exciting, feel free to reach out!
                    </p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-item">
                                <i class="fas fa-envelope contact-icon"></i>
                                <div>
                                    <strong>Email</strong><br>
                                    <a href="mailto:jawaharganesh99jg@gmail.com" style="color: var(--accent-primary); text-decoration: none;">
                                        jawaharganesh99jg@gmail.com
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-item">
                                <i class="fab fa-linkedin contact-icon"></i>
                                <div>
                                    <strong>LinkedIn</strong><br>
                                    <a href="https://linkedin.com/in/jay71" target="_blank" style="color: var(--accent-primary); text-decoration: none;">
                                        linkedin.com/in/jay71
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="contact-item">
                                <i class="fas fa-certificate contact-icon"></i>
                                <div>
                                    <strong>Certifications</strong><br>
                                    <a href="https://www.credly.com/users/jawahar-ganesh" target="_blank" style="color: var(--accent-primary); text-decoration: none;">
                                        credly.com/users/jawahar-ganesh
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-item">
                                <i class="fab fa-github contact-icon"></i>
                                <div>
                                    <strong>GitHub</strong><br>
                                    <a href="https://github.com/jawahar-ganesh" target="_blank" style="color: var(--accent-primary); text-decoration: none;">
                                        github.com/jawahar-ganesh
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-item">
                                <i class="fas fa-graduation-cap contact-icon"></i>
                                <div>
                                    <strong>Education</strong><br>
                                    <span style="color: var(--text-secondary);">Bachelor Honours in Computer Engineering<br>University Malaysia Perlis (UniMAP)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h4 class="mb-3">What I'm Interested In:</h4>
                        <div class="row text-center">
                            <div class="col-md-3">
                            <div class="skill-item">
                                <i class="fas fa-robot skill-icon"></i>
                                <h6>AI/ML</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="skill-item">
                                <i class="fas fa-microchip skill-icon"></i>
                                <h6>Hardware/IoT</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="skill-item">
                                <i class="fas fa-briefcase skill-icon"></i>
                                <h6>Business</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="skill-item">
                                <i class="fas fa-lightbulb skill-icon"></i>
                                <h6>Innovation</h6>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="mailto:jawaharganesh99jg@gmail.com" class="btn btn-primary-custom me-3">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                        <a href="https://linkedin.com/in/jay71" target="_blank" class="btn btn-primary-custom me-3">
                            <i class="fab fa-linkedin me-2"></i>Connect on LinkedIn
                        </a>
                        <a href="https://www.credly.com/users/jawahar-ganesh" target="_blank" class="btn btn-primary-custom">
                            <i class="fas fa-certificate me-2"></i>View Certifications
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8 mt-5">
                <div class="contact-form-container">
                    <h3 class="mb-4">Send me a Message</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ secure_url(route('contact.submit')) }}" class="contact-form" data-secure="true" onsubmit="return submitSecurely(this)">
                        @csrf
                        
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Email Field -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Organization Field -->
                            <div class="col-md-6 mb-3">
                                <label for="organization" class="form-label">Organization/Company</label>
                                <input type="text" 
                                       class="form-control @error('organization') is-invalid @enderror" 
                                       id="organization" 
                                       name="organization" 
                                       value="{{ old('organization') }}" 
                                       placeholder="e.g., Google, Microsoft, Startup Name">
                                @error('organization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- University Field -->
                            <div class="col-md-6 mb-3">
                                <label for="university" class="form-label">University/Institution</label>
                                <input type="text" 
                                       class="form-control @error('university') is-invalid @enderror" 
                                       id="university" 
                                       name="university" 
                                       value="{{ old('university') }}" 
                                       placeholder="e.g., University Malaysia Perlis, MIT">
                                @error('university')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Phone Field -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="+60 12-345 6789">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Message Field -->
                        <div class="mb-4">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      required 
                                      placeholder="Tell me about your project, collaboration ideas, or any questions you have...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Contact Section -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card-custom text-center">
                    <i class="fas fa-clock card-icon"></i>
                    <h3 class="card-title">Response Time</h3>
                    <p class="card-text">
                        I typically respond to emails within 24 hours. For urgent matters, 
                        feel free to reach out via LinkedIn or GitHub.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom text-center">
                    <i class="fas fa-handshake card-icon"></i>
                    <h3 class="card-title">Collaboration</h3>
                    <p class="card-text">
                        Open to collaborating on AI projects, cybersecurity solutions, hardware 
                        development, and innovative software applications.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-custom text-center">
                    <i class="fas fa-map-marker-alt card-icon"></i>
                    <h3 class="card-title">Location</h3>
                    <p class="card-text">
                        Based in Malaysia, Bachelor Honours in Computer Engineering graduate from University Malaysia Perlis (UniMAP). 
                        Available for remote collaboration and international projects.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .contact-form-container {
        background: rgba(26, 26, 26, 0.8);
        border: 1px solid #333;
        border-radius: 12px;
        padding: 2rem;
        backdrop-filter: blur(10px);
    }
    
    .contact-form .form-label {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .contact-form .form-control {
        background-color: rgba(42, 42, 42, 0.8);
        border: 1px solid #333;
        color: #ffffff;
        padding: 12px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .contact-form .form-control:focus {
        background-color: rgba(42, 42, 42, 0.9);
        border-color: #8b5cf6;
        color: #ffffff;
        box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
    }
    
    .contact-form .form-control::placeholder {
        color: #666;
    }
    
    .contact-form .btn-primary-custom {
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
        border: none;
        padding: 12px 32px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .contact-form .btn-primary-custom:hover {
        background: linear-gradient(135deg, #7c3aed, #9333ea);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
        border-radius: 8px;
    }
    
    .invalid-feedback {
        color: #ff6b6b;
        font-size: 0.875rem;
    }
    
    .form-control.is-invalid {
        border-color: #ff6b6b;
    }
    
    .form-control.is-invalid:focus {
        border-color: #ff6b6b;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
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
