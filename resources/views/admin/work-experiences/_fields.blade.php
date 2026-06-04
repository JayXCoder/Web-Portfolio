@php $we = $workExperience ?? null; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div><label class="label-field">Position *</label><input name="position" value="{{ old('position', $we?->position) }}" required class="input-field"></div>
    <div><label class="label-field">Company *</label><input name="company" value="{{ old('company', $we?->company) }}" required class="input-field"></div>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="label-field">Employment type *</label>
        <select name="employment_type" required class="input-field">
            @foreach(['Full-Time','Part-Time','Internship','Contract','Freelance'] as $t)
            <option value="{{ $t }}" @selected(old('employment_type', $we?->employment_type) === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div><label class="label-field">Location</label><input name="location" value="{{ old('location', $we?->location) }}" class="input-field"></div>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div><label class="label-field">Start date *</label><input type="date" name="start_date" value="{{ old('start_date', $we?->start_date?->format('Y-m-d')) }}" required class="input-field"></div>
    <div><label class="label-field">End date</label><input type="date" name="end_date" value="{{ old('end_date', $we?->end_date?->format('Y-m-d')) }}" class="input-field"></div>
</div>
<label class="flex items-center gap-2 text-sm text-text-muted cursor-pointer"><input type="checkbox" name="is_current" value="1" @checked(old('is_current', $we?->is_current))> Current role</label>
<div><label class="label-field">Description *</label><textarea name="description" rows="4" required class="input-field">{{ old('description', $we?->description) }}</textarea></div>
<div><label class="label-field">Technologies (comma-separated)</label><input name="technologies" value="{{ old('technologies', $we && $we->technologies ? implode(', ', $we->technologies) : '') }}" class="input-field"></div>
<div><label class="label-field">Company logo</label><input type="file" name="company_logo" accept="image/*" class="input-field file:mr-4 file:rounded-lg file:border-0 file:bg-uv file:px-3 file:py-1.5 file:text-sm file:text-white"></div>
<label class="flex items-center gap-2 text-sm cursor-pointer"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $we?->is_published ?? true))> Published</label>
