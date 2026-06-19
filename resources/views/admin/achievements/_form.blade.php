@php $achievement = $achievement ?? null; @endphp

<form method="POST" action="{{ $action }}" class="card-surface max-w-3xl p-6 space-y-4">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title" class="label-field">Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $achievement?->title) }}" required class="input-field">
        </div>
        <div>
            <label for="organization" class="label-field">Organization *</label>
            <input type="text" id="organization" name="organization" value="{{ old('organization', $achievement?->organization) }}" required class="input-field">
        </div>
    </div>

    <div>
        <label for="story" class="label-field">Story * <span class="text-text-dim font-normal">(what you did / how you earned it)</span></label>
        <textarea id="story" name="story" rows="5" required class="input-field">{{ old('story', $achievement?->story) }}</textarea>
    </div>

    <div>
        <label for="project" class="label-field">Related project</label>
        <textarea id="project" name="project" rows="2" class="input-field">{{ old('project', $achievement?->project) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="issued_date" class="label-field">Issued date</label>
            <input type="date" id="issued_date" name="issued_date" value="{{ old('issued_date', $achievement?->issued_date?->format('Y-m-d')) }}" class="input-field">
        </div>
        <div>
            <label for="sort_order" class="label-field">Sort order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $achievement?->sort_order ?? 0) }}" min="0" class="input-field">
            <p class="mt-1 text-xs text-text-dim">Lower numbers appear first.</p>
        </div>
    </div>

    <div>
        <label for="credly_url" class="label-field">Credly badge URL</label>
        <input type="url" id="credly_url" name="credly_url" value="{{ old('credly_url', $achievement?->credly_url) }}" class="input-field" placeholder="https://www.credly.com/...">
    </div>

    <div>
        <label for="image_url" class="label-field">Badge image URL</label>
        <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $achievement?->image_url) }}" class="input-field" placeholder="https://images.credly.com/...">
    </div>

    <div>
        <label for="skills" class="label-field">Skills <span class="text-text-dim font-normal">(comma-separated)</span></label>
        <input type="text" id="skills" name="skills" value="{{ old('skills', $achievement?->skills_string ?? '') }}" class="input-field">
    </div>

    <label class="flex items-center gap-2 cursor-pointer text-sm text-text-muted">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $achievement?->is_published ?? true)) class="rounded border-border text-uv">
        Published
    </label>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="btn-primary">Save achievement</button>
        <a href="{{ route('admin.achievements') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
