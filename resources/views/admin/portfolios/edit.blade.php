@extends('layouts.app')

@section('title', 'Edit Portfolio - Admin')

@section('content')
<div class="container-fluid" style="padding-top: 100px; padding-bottom: 2rem;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-white mb-1">
                        <i class="fas fa-edit me-2"></i>
                        Edit Portfolio
                    </h1>
                    <p class="text-muted mb-0">Update portfolio information</p>
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

    <!-- Edit Form -->
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

                    <form method="POST" action="{{ secure_url(route('admin.portfolios.update', $portfolio)) }}" enctype="multipart/form-data" data-secure="true" onsubmit="return submitSecurely(this)">
                        @csrf
                        @method('PUT')

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
                                       value="{{ old('title', $portfolio->title) }}" 
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
                                    <option value="AI/ML" {{ old('category', $portfolio->category) == 'AI/ML' ? 'selected' : '' }}>AI/ML</option>
                                    <option value="Web Development" {{ old('category', $portfolio->category) == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                    <option value="Mobile App" {{ old('category', $portfolio->category) == 'Mobile App' ? 'selected' : '' }}>Mobile App</option>
                                    <option value="Desktop App" {{ old('category', $portfolio->category) == 'Desktop App' ? 'selected' : '' }}>Desktop App</option>
                                    <option value="Hardware" {{ old('category', $portfolio->category) == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                    <option value="Cybersecurity" {{ old('category', $portfolio->category) == 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                                    <option value="Other" {{ old('category', $portfolio->category) == 'Other' ? 'selected' : '' }}>Other</option>
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
                                      required>{{ old('short_description', $portfolio->short_description) }}</textarea>
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
                                      required>{{ old('description', $portfolio->description) }}</textarea>
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
                                       value="{{ old('duration_months', $portfolio->duration_months) }}" 
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
                                       value="{{ old('client', $portfolio->client) }}">
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
                                      required>{{ old('technologies', is_array($portfolio->technologies) ? implode(', ', $portfolio->technologies) : $portfolio->technologies) }}</textarea>
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
                                      required>{{ old('features', is_array($portfolio->features) ? implode(', ', $portfolio->features) : $portfolio->features) }}</textarea>
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
                                          rows="3">{{ old('challenges', $portfolio->challenges) }}</textarea>
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
                                          rows="3">{{ old('solutions', $portfolio->solutions) }}</textarea>
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
                            
                            <!-- Current Images Display -->
                            @if($portfolio->images && count($portfolio->images) > 0)
                                <div class="current-images mb-3">
                                    <h6 class="text-white mb-2">Current Images:</h6>
                                    <div class="row">
                                        @foreach($portfolio->images as $index => $image)
                                            <div class="col-md-4 mb-2">
                                                <div class="current-image-item">
                                                    <img src="{{ $image }}" alt="Current image {{ $index + 1 }}" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
                                                    <div class="image-actions mt-1">
                                                        <small class="text-muted">Image {{ $index + 1 }}</small>
                                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeCurrentImage({{ $index }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="existing_images[{{ $index }}]" value="{{ $image }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Image Upload Section -->
                            <div class="image-upload-section mb-3">
                                <h6 class="text-white mb-2">Add New Images:</h6>
                                <div class="row" id="imageUploadArea">
                                    <!-- Image upload slots -->
                                    <div class="col-md-4 mb-3">
                                        <div class="image-upload-slot">
                                            <input type="file" 
                                                   class="form-control image-input d-none" 
                                                   name="new_images[]" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 0)">
                                            <div class="upload-placeholder" onclick="document.querySelectorAll('.image-input')[0].click()">
                                                <i class="fas fa-plus fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Click to upload image</p>
                                                <small class="text-muted">Max 5MB, JPG/PNG/WEBP</small>
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
                                                   name="new_images[]" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 1)">
                                            <div class="upload-placeholder" onclick="document.querySelectorAll('.image-input')[1].click()">
                                                <i class="fas fa-plus fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Click to upload image</p>
                                                <small class="text-muted">Max 5MB, JPG/PNG/WEBP</small>
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
                                                   name="new_images[]" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 2)">
                                            <div class="upload-placeholder" onclick="document.querySelectorAll('.image-input')[2].click()">
                                                <i class="fas fa-plus fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Click to upload image</p>
                                                <small class="text-muted">Max 5MB, JPG/PNG/WEBP</small>
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
                                        Or add new image URLs
                                    </label>
                                    <textarea class="form-control @error('new_image_urls') is-invalid @enderror" 
                                              name="new_image_urls" 
                                              rows="2" 
                                              placeholder="Enter new image URLs separated by commas">{{ old('new_image_urls') }}</textarea>
                                    @error('new_image_urls')
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
                                           {{ old('is_featured', $portfolio->is_featured) ? 'checked' : '' }}>
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
                                           {{ old('is_published', $portfolio->is_published) ? 'checked' : '' }}>
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
                                       value="{{ old('sort_order', $portfolio->sort_order) }}" 
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
                                Update Portfolio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Portfolio Preview -->
        <div class="col-lg-4">
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-secondary">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-eye me-2"></i>
                        Portfolio Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($portfolio->images && count($portfolio->images) > 0)
                            <img src="{{ $portfolio->images[0] }}" 
                                 alt="{{ $portfolio->title }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <h6 class="text-white">{{ $portfolio->title }}</h6>
                    <p class="text-muted small">{{ $portfolio->short_description }}</p>

                    <div class="mb-2">
                        <strong class="text-white">Category:</strong>
                        <span class="badge bg-primary ms-1">{{ $portfolio->category }}</span>
                    </div>

                    @if($portfolio->technologies)
                        <div class="mb-2">
                            <strong class="text-white">Technologies:</strong>
                            <div class="mt-1">
                                @foreach(is_array($portfolio->technologies) ? $portfolio->technologies : explode(', ', $portfolio->technologies) as $tech)
                                    <span class="badge bg-secondary me-1 mb-1">{{ trim($tech) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($portfolio->duration_months)
                        <div class="mb-2">
                            <strong class="text-white">Duration:</strong>
                            <span class="text-muted">{{ $portfolio->duration_months }} month(s)</span>
                        </div>
                    @endif

                    @if($portfolio->client)
                        <div class="mb-2">
                            <strong class="text-white">Client:</strong>
                            <span class="text-muted">{{ $portfolio->client }}</span>
                        </div>
                    @endif

                    <div class="mt-3">
                        @if($portfolio->is_featured)
                            <span class="badge bg-warning me-1">Featured</span>
                        @endif
                        @if($portfolio->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
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

    .current-image-item {
        position: relative;
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 8px;
        padding: 0.5rem;
        background: rgba(139, 92, 246, 0.05);
    }

    .image-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
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

function removeCurrentImage(index) {
    const imageItem = document.querySelector(`input[name="existing_images[${index}]"]`).closest('.current-image-item');
    if (imageItem) {
        imageItem.style.display = 'none';
        
        // Add hidden input to mark for removal
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_images[]';
        hiddenInput.value = index;
        form.appendChild(hiddenInput);
    }
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
