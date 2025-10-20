@extends('layouts.app')

@section('title', 'Create Portfolio - Admin')

@section('content')
<div class="container-fluid" style="padding-top: 100px; padding-bottom: 2rem;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-white mb-1">
                        <i class="fas fa-plus me-2"></i>
                        Create New Portfolio
                    </h1>
                    <p class="text-white mb-0">Add a new project to your portfolio</p>
                </div>
                <div class="d-flex gap-2 mt-3 mt-md-0">
                    <a href="{{ route('admin.portfolios') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Portfolios
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">
                        <i class="fas fa-home me-1"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-secondary">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-briefcase me-2"></i>
                        Portfolio Information
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

                    <form method="POST" action="{{ secure_url(route('admin.portfolios.store')) }}" enctype="multipart/form-data" data-secure="true" onsubmit="return submitSecurely(this)">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label text-white">
                                    <i class="fas fa-heading me-1"></i>
                                    Project Title *
                                </label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label text-white">
                                    <i class="fas fa-tags me-1"></i>
                                    Category *
                                </label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" 
                                        name="category" 
                                        required>
                                    <option value="">Select Category</option>
                                    <option value="AI/ML" {{ old('category') == 'AI/ML' ? 'selected' : '' }}>AI/ML</option>
                                    <option value="Web Development" {{ old('category') == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                    <option value="Mobile App" {{ old('category') == 'Mobile App' ? 'selected' : '' }}>Mobile App</option>
                                    <option value="Desktop App" {{ old('category') == 'Desktop App' ? 'selected' : '' }}>Desktop App</option>
                                    <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                    <option value="Cybersecurity" {{ old('category') == 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label text-white">
                                <i class="fas fa-align-left me-1"></i>
                                Short Description *
                            </label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                      id="short_description" 
                                      name="short_description" 
                                      rows="3" 
                                      required>{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label text-white">
                                <i class="fas fa-file-text me-1"></i>
                                Full Description *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="6" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duration_months" class="form-label text-white">
                                    <i class="fas fa-clock me-1"></i>
                                    Development Duration (Months)
                                </label>
                                <input type="number" 
                                       class="form-control @error('duration_months') is-invalid @enderror" 
                                       id="duration_months" 
                                       name="duration_months" 
                                       value="{{ old('duration_months') }}" 
                                       min="1" max="60">
                                @error('duration_months')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="client" class="form-label text-white">
                                    <i class="fas fa-building me-1"></i>
                                    Client/Organization
                                </label>
                                <input type="text" 
                                       class="form-control @error('client') is-invalid @enderror" 
                                       id="client" 
                                       name="client" 
                                       value="{{ old('client') }}">
                                @error('client')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="technologies" class="form-label text-white">
                                <i class="fas fa-code me-1"></i>
                                Technologies Used *
                            </label>
                            <textarea class="form-control @error('technologies') is-invalid @enderror" 
                                      id="technologies" 
                                      name="technologies" 
                                      rows="3" 
                                      placeholder="Enter technologies separated by commas (e.g., Python, PyQt6, OpenCV, TensorFlow)" 
                                      required>{{ old('technologies') }}</textarea>
                            @error('technologies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="features" class="form-label text-white">
                                <i class="fas fa-star me-1"></i>
                                Key Features *
                            </label>
                            <textarea class="form-control @error('features') is-invalid @enderror" 
                                      id="features" 
                                      name="features" 
                                      rows="4" 
                                      placeholder="Enter features separated by commas (e.g., Real-time processing, AI monitoring, Secure interface)" 
                                      required>{{ old('features') }}</textarea>
                            @error('features')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="challenges" class="form-label text-white">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Challenges
                                </label>
                                <textarea class="form-control @error('challenges') is-invalid @enderror" 
                                          id="challenges" 
                                          name="challenges" 
                                          rows="3">{{ old('challenges') }}</textarea>
                                @error('challenges')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="solutions" class="form-label text-white">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Solutions
                                </label>
                                <textarea class="form-control @error('solutions') is-invalid @enderror" 
                                          id="solutions" 
                                          name="solutions" 
                                          rows="3">{{ old('solutions') }}</textarea>
                                @error('solutions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label text-white">
                                <i class="fas fa-images me-1"></i>
                                Portfolio Images
                            </label>
                            
                            <!-- Image Upload Section -->
                            <div class="image-upload-section mb-3">
                                <div class="row" id="imageUploadArea">
                                    <!-- Image upload slots -->
                                    <div class="col-md-4 mb-3">
                                        <div class="image-upload-slot">
                                            <input type="file" 
                                                   class="form-control image-input d-none" 
                                                   name="images[]" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 0)">
                                            <div class="upload-placeholder" onclick="document.querySelector('.image-input').click()">
                                                <i class="fas fa-plus fa-2x text-white mb-2"></i>
                                                <p class="text-white mb-0">Click to upload image</p>
                                                <small class="text-white-50">Max 25MB, JPG/PNG/WEBP</small>
                                            </div>
                                            <div class="image-preview d-none">
                                                <img src="" alt="Preview" class="img-fluid rounded">
                                                <button type="button" class="btn btn-sm btn-danger remove-image" onclick="removeImage(0)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="image-upload-slot">
                                            <input type="file" 
                                                   class="form-control image-input d-none" 
                                                   name="images[]" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 1)">
                                            <div class="upload-placeholder" onclick="document.querySelectorAll('.image-input')[1].click()">
                                                <i class="fas fa-plus fa-2x text-white mb-2"></i>
                                                <p class="text-white mb-0">Click to upload image</p>
                                                <small class="text-white-50">Max 25MB, JPG/PNG/WEBP</small>
                                            </div>
                                            <div class="image-preview d-none">
                                                <img src="" alt="Preview" class="img-fluid rounded">
                                                <button type="button" class="btn btn-sm btn-danger remove-image" onclick="removeImage(1)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="image-upload-slot">
                                            <input type="file" 
                                                   class="form-control image-input d-none" 
                                                   name="images[]" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 2)">
                                            <div class="upload-placeholder" onclick="document.querySelectorAll('.image-input')[2].click()">
                                                <i class="fas fa-plus fa-2x text-white mb-2"></i>
                                                <p class="text-white mb-0">Click to upload image</p>
                                                <small class="text-white-50">Max 25MB, JPG/PNG/WEBP</small>
                                            </div>
                                            <div class="image-preview d-none">
                                                <img src="" alt="Preview" class="img-fluid rounded">
                                                <button type="button" class="btn btn-sm btn-danger remove-image" onclick="removeImage(2)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Alternative: URL Input -->
                                <div class="mt-3">
                                    <label class="form-label text-white">
                                        <i class="fas fa-link me-1"></i>
                                        Or add image URLs
                                    </label>
                                    <textarea class="form-control @error('image_urls') is-invalid @enderror" 
                                              name="image_urls" 
                                              rows="2" 
                                              placeholder="Enter image URLs separated by commas (alternative to file upload)">{{ old('image_urls') }}</textarea>
                                    @error('image_urls')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            @error('images')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_featured" 
                                           name="is_featured" 
                                           value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="is_featured">
                                        <i class="fas fa-star me-1"></i>
                                        Featured Project
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_published" 
                                           name="is_published" 
                                           value="1" 
                                           {{ old('is_published', true) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="is_published">
                                        <i class="fas fa-eye me-1"></i>
                                        Published
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="sort_order" class="form-label text-white">
                                    <i class="fas fa-sort me-1"></i>
                                    Sort Order
                                </label>
                                <input type="number" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', 0) }}" 
                                       min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.portfolios') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Create Portfolio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="col-lg-4">
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-secondary">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i>
                        Tips for Creating Portfolios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-white">
                            <i class="fas fa-lightbulb me-1"></i>
                            Best Practices
                        </h6>
                        <ul class="list-unstyled text-white small">
                            <li class="mb-1">• Use descriptive titles</li>
                            <li class="mb-1">• Include specific technologies</li>
                            <li class="mb-1">• Highlight key features</li>
                            <li class="mb-1">• Mention challenges and solutions</li>
                            <li class="mb-1">• Add high-quality images</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-white">
                            <i class="fas fa-tags me-1"></i>
                            Categories
                        </h6>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-primary">AI/ML</span>
                            <span class="badge bg-success">Web Dev</span>
                            <span class="badge bg-info">Mobile</span>
                            <span class="badge bg-warning">Desktop</span>
                            <span class="badge bg-secondary">Hardware</span>
                            <span class="badge bg-danger">Security</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-white">
                            <i class="fas fa-star me-1"></i>
                            Featured Projects
                        </h6>
                        <p class="text-white small mb-0">
                            Featured projects appear prominently on your portfolio homepage. 
                            Choose your best work to showcase.
                        </p>
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

    .form-select {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
    }

    .form-select:focus {
        background-color: rgba(255, 255, 255, 0.08);
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        color: white;
    }

    .form-check-input:checked {
        background-color: var(--accent-primary);
        border-color: var(--accent-primary);
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

    .badge {
        font-size: 0.75rem;
    }

    /* Image Upload Styles */
    .image-upload-slot {
        position: relative;
        border: 2px dashed rgba(139, 92, 246, 0.3);
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
        background: rgba(139, 92, 246, 0.05);
    }

    .image-upload-slot:hover {
        border-color: var(--accent-primary);
        background: rgba(139, 92, 246, 0.1);
    }

    .upload-placeholder {
        cursor: pointer;
        padding: 2rem 1rem;
        transition: all 0.3s ease;
    }

    .upload-placeholder:hover {
        transform: scale(1.05);
    }

    .image-preview {
        position: relative;
        padding: 0.5rem;
    }

    .image-preview img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 6px;
    }

    .remove-image {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .image-upload-slot.has-image .upload-placeholder {
        display: none;
    }

    .image-upload-slot.has-image .image-preview {
        display: block !important;
    }
</style>
@endpush

@push('scripts')
<script>
function previewImage(input, index) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const uploadSlot = input.closest('.image-upload-slot');
        
        reader.onload = function(e) {
            const preview = uploadSlot.querySelector('.image-preview img');
            preview.src = e.target.result;
            uploadSlot.classList.add('has-image');
            uploadSlot.querySelector('.image-preview').classList.remove('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(index) {
    const uploadSlots = document.querySelectorAll('.image-upload-slot');
    const uploadSlot = uploadSlots[index];
    const input = uploadSlot.querySelector('input[type="file"]');
    
    // Reset file input
    input.value = '';
    
    // Hide preview and show placeholder
    uploadSlot.classList.remove('has-image');
    uploadSlot.querySelector('.image-preview').classList.add('d-none');
}

// File size validation
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.size > 5 * 1024 * 1024) { // 5MB
            alert('File size must be less than 5MB');
            this.value = '';
        }
    });
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
