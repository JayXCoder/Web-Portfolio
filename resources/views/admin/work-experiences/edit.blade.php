@extends('layouts.app')

@section('title', 'Edit Work Experience - Admin - Jawahar Ganesh @ Jay')

@section('content')
<div class="container-fluid" style="padding-top: 100px; padding-bottom: 2rem;">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-edit me-2"></i>
                            Edit Work Experience
                        </h1>
                        <p class="page-subtitle">Update work experience: {{ $workExperience->position }} at {{ $workExperience->company }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.work-experiences') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Edit Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2"></i>
                        Work Experience Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-experiences.update', $workExperience) }}" method="POST" enctype="multipart/form-data" onsubmit="return submitSecurely(this)">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="form-section mb-4">
                                    <h6 class="section-title">Basic Information</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="company" class="form-label text-white">
                                                <i class="fas fa-building me-1"></i>
                                                Company Name *
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('company') is-invalid @enderror" 
                                                   id="company" 
                                                   name="company" 
                                                   value="{{ old('company', $workExperience->company) }}" 
                                                   required>
                                            @error('company')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="position" class="form-label text-white">
                                                <i class="fas fa-user-tie me-1"></i>
                                                Position Title *
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('position') is-invalid @enderror" 
                                                   id="position" 
                                                   name="position" 
                                                   value="{{ old('position', $workExperience->position) }}" 
                                                   required>
                                            @error('position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="employment_type" class="form-label text-white">
                                                <i class="fas fa-briefcase me-1"></i>
                                                Employment Type *
                                            </label>
                                            <select class="form-select @error('employment_type') is-invalid @enderror" 
                                                    id="employment_type" 
                                                    name="employment_type" 
                                                    required>
                                                <option value="">Select Employment Type</option>
                                                <option value="Full-Time" {{ old('employment_type', $workExperience->employment_type) == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                                <option value="Part-Time" {{ old('employment_type', $workExperience->employment_type) == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                                                <option value="Internship" {{ old('employment_type', $workExperience->employment_type) == 'Internship' ? 'selected' : '' }}>Internship</option>
                                                <option value="Contract" {{ old('employment_type', $workExperience->employment_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                                <option value="Freelance" {{ old('employment_type', $workExperience->employment_type) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                            </select>
                                            @error('employment_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="location" class="form-label text-white">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                Location
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('location') is-invalid @enderror" 
                                                   id="location" 
                                                   name="location" 
                                                   value="{{ old('location', $workExperience->location) }}" 
                                                   placeholder="e.g., New York, NY">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Duration Information -->
                                <div class="form-section mb-4">
                                    <h6 class="section-title">Duration & Status</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="start_date" class="form-label text-white">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Start Date *
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" 
                                                   name="start_date" 
                                                   value="{{ old('start_date', $workExperience->start_date->format('Y-m-d')) }}" 
                                                   required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="end_date" class="form-label text-white">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                End Date
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" 
                                                   name="end_date" 
                                                   value="{{ old('end_date', $workExperience->end_date ? $workExperience->end_date->format('Y-m-d') : '') }}">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_current" 
                                                   name="is_current" 
                                                   value="1" 
                                                   {{ old('is_current', $workExperience->is_current) ? 'checked' : '' }}
                                                   onchange="toggleEndDate()">
                                            <label class="form-check-label text-white" for="is_current">
                                                <i class="fas fa-circle me-1"></i>
                                                This is my current position
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-section mb-4">
                                    <h6 class="section-title">Description</h6>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-white">
                                            <i class="fas fa-align-left me-1"></i>
                                            Job Description *
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="4" 
                                                  required>{{ old('description', $workExperience->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="responsibilities" class="form-label text-white">
                                            <i class="fas fa-tasks me-1"></i>
                                            Key Responsibilities
                                        </label>
                                        <textarea class="form-control @error('responsibilities') is-invalid @enderror" 
                                                  id="responsibilities" 
                                                  name="responsibilities" 
                                                  rows="4">{{ old('responsibilities', $workExperience->responsibilities) }}</textarea>
                                        @error('responsibilities')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Technologies & Skills -->
                                <div class="form-section mb-4">
                                    <h6 class="section-title">Technologies & Skills</h6>
                                    
                                    <div class="mb-3">
                                        <label for="technologies" class="form-label text-white">
                                            <i class="fas fa-code me-1"></i>
                                            Technologies & Tools
                                        </label>
                                        <textarea class="form-control @error('technologies') is-invalid @enderror" 
                                                  id="technologies" 
                                                  name="technologies" 
                                                  rows="2" 
                                                  placeholder="Enter technologies separated by commas (e.g., PHP, Laravel, MySQL, JavaScript)">{{ old('technologies', $workExperience->technologies_string) }}</textarea>
                                        @error('technologies')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="skills_gained" class="form-label text-white">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            Skills Gained
                                        </label>
                                        <textarea class="form-control @error('skills_gained') is-invalid @enderror" 
                                                  id="skills_gained" 
                                                  name="skills_gained" 
                                                  rows="2" 
                                                  placeholder="Enter skills separated by commas (e.g., Team Leadership, Project Management, Agile Development)">{{ old('skills_gained', $workExperience->skills_gained_string) }}</textarea>
                                        @error('skills_gained')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="achievements" class="form-label text-white">
                                            <i class="fas fa-trophy me-1"></i>
                                            Key Achievements
                                        </label>
                                        <textarea class="form-control @error('achievements') is-invalid @enderror" 
                                                  id="achievements" 
                                                  name="achievements" 
                                                  rows="2" 
                                                  placeholder="Enter achievements separated by commas (e.g., Increased efficiency by 30%, Led team of 5 developers)">{{ old('achievements', $workExperience->achievements_string) }}</textarea>
                                        @error('achievements')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <div class="form-section mb-4">
                                    <h6 class="section-title">Additional Information</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="team_size" class="form-label text-white">
                                                <i class="fas fa-users me-1"></i>
                                                Team Size
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('team_size') is-invalid @enderror" 
                                                   id="team_size" 
                                                   name="team_size" 
                                                   value="{{ old('team_size', $workExperience->team_size) }}" 
                                                   min="1" 
                                                   placeholder="e.g., 5">
                                            @error('team_size')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="reporting_to" class="form-label text-white">
                                                <i class="fas fa-user-tie me-1"></i>
                                                Reporting To
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('reporting_to') is-invalid @enderror" 
                                                   id="reporting_to" 
                                                   name="reporting_to" 
                                                   value="{{ old('reporting_to', $workExperience->reporting_to) }}" 
                                                   placeholder="e.g., CTO, Project Manager">
                                            @error('reporting_to')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="salary_range" class="form-label text-white">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            Salary Range (Optional)
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('salary_range') is-invalid @enderror" 
                                               id="salary_range" 
                                               name="salary_range" 
                                               value="{{ old('salary_range', $workExperience->salary_range) }}" 
                                               placeholder="e.g., $80,000 - $100,000">
                                        @error('salary_range')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <!-- Company Logo -->
                                <div class="sidebar-section mb-4">
                                    <h6 class="section-title">Company Logo</h6>
                                    <div class="logo-upload-section">
                                        @if($workExperience->company_logo)
                                            <div class="current-logo mb-3">
                                                <label class="form-label text-white">Current Logo:</label>
                                                @if(filter_var($workExperience->company_logo, FILTER_VALIDATE_URL))
                                                    <img src="{{ $workExperience->company_logo }}" alt="Current Logo" class="img-fluid rounded current-logo-img">
                                                @else
                                                    <img src="{{ route('company.logo', basename($workExperience->company_logo)) }}" alt="Current Logo" class="img-fluid rounded current-logo-img">
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="logo-upload-area">
                                            <input type="file" 
                                                   class="form-control logo-input d-none" 
                                                   id="company_logo" 
                                                   name="company_logo" 
                                                   accept="image/*"
                                                   onchange="previewLogo(this)">
                                            <div class="logo-upload-placeholder" onclick="document.getElementById('company_logo').click()">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-white mb-2"></i>
                                                <p class="text-white mb-0">Click to upload new logo</p>
                                                <small class="text-white-50">Max 2MB, JPG/PNG/GIF</small>
                                            </div>
                                            <div class="logo-preview d-none">
                                                <img src="" alt="Logo Preview" class="img-fluid rounded">
                                                <button type="button" class="btn btn-sm btn-danger remove-logo" onclick="removeLogo()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('company_logo')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Settings -->
                                <div class="sidebar-section mb-4">
                                    <h6 class="section-title">Settings</h6>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_published" 
                                                   name="is_published" 
                                                   value="1" 
                                                   {{ old('is_published', $workExperience->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label text-white" for="is_published">
                                                <i class="fas fa-eye me-1"></i>
                                                Published
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label text-white">
                                            <i class="fas fa-sort me-1"></i>
                                            Sort Order
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" 
                                               name="sort_order" 
                                               value="{{ old('sort_order', $workExperience->sort_order) }}" 
                                               min="0" 
                                               placeholder="Leave empty for auto">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.work-experiences') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-save me-2"></i>Update Work Experience
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .page-header {
        background: var(--dark-secondary);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .page-title {
        color: var(--text-primary);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        margin: 0;
    }

    .card {
        background: var(--dark-secondary);
        border: 1px solid var(--border-color);
        border-radius: 15px;
    }

    .card-header {
        background: var(--dark-tertiary);
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    .card-title {
        color: var(--text-primary);
        margin: 0;
    }

    .form-section {
        background: var(--dark-tertiary);
        padding: 1.5rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .section-title {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--accent-primary);
    }

    .form-control,
    .form-select {
        background: var(--dark-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .form-control:focus,
    .form-select:focus {
        background: var(--dark-bg);
        border-color: var(--accent-primary);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .sidebar-section {
        background: var(--dark-tertiary);
        padding: 1.5rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .current-logo-img {
        max-width: 200px;
        max-height: 200px;
        border: 1px solid var(--border-color);
    }

    .logo-upload-area {
        position: relative;
        text-align: center;
    }

    .logo-upload-placeholder {
        border: 2px dashed var(--border-color);
        border-radius: 10px;
        padding: 2rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .logo-upload-placeholder:hover {
        border-color: var(--accent-primary);
        background: rgba(139, 92, 246, 0.1);
    }

    .logo-preview {
        position: relative;
        text-align: center;
    }

    .logo-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .remove-logo {
        position: absolute;
        top: 10px;
        right: 10px;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-actions {
        background: var(--dark-tertiary);
        padding: 1.5rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        margin-top: 2rem;
    }

    .btn-outline-secondary {
        border-color: var(--border-color);
        color: var(--text-secondary);
    }

    .btn-outline-secondary:hover {
        background: var(--border-color);
        border-color: var(--border-color);
        color: var(--text-primary);
    }
</style>
@endpush

@push('scripts')
<script>
function toggleEndDate() {
    const isCurrent = document.getElementById('is_current').checked;
    const endDateInput = document.getElementById('end_date');
    
    if (isCurrent) {
        endDateInput.disabled = true;
        endDateInput.value = '';
    } else {
        endDateInput.disabled = false;
    }
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.querySelector('.logo-preview img');
            preview.src = e.target.result;
            document.querySelector('.logo-upload-placeholder').classList.add('d-none');
            document.querySelector('.logo-preview').classList.remove('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    document.getElementById('company_logo').value = '';
    document.querySelector('.logo-upload-placeholder').classList.remove('d-none');
    document.querySelector('.logo-preview').classList.add('d-none');
}

// Force HTTPS form submission
function submitSecurely(form) {
    const currentUrl = window.location.href;
    if (currentUrl.startsWith('https://')) {
        form.action = form.action.replace('http://', 'https://');
    }
    return true;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    toggleEndDate(); // Set initial state
});
</script>
@endpush
@endsection
