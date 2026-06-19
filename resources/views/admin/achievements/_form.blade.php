@php
    $achievement = $achievement ?? null;
    $types = config('achievements.types', []);
    $selectedType = old('type', $achievement?->type ?? 'certificate');
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="card-surface max-w-3xl p-6 space-y-4">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="type" class="label-field">Type *</label>
        <select id="type" name="type" required class="input-field">
            @foreach ($types as $key => $type)
            <option value="{{ $key }}" @selected($selectedType === $key)>{{ $type['label'] }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-text-dim">Certificate, award, competition win, stage performance, and more.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title" class="label-field">Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $achievement?->title) }}" required class="input-field" placeholder="e.g. 1st Place — Hackathon 2025">
        </div>
        <div>
            <label for="placement" class="label-field">Placement / result</label>
            <input type="text" id="placement" name="placement" value="{{ old('placement', $achievement?->placement) }}" class="input-field" placeholder="e.g. Champion, 1st Place, Runner-up">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="organization" class="label-field">Organization / event host *</label>
            <input type="text" id="organization" name="organization" value="{{ old('organization', $achievement?->organization) }}" required class="input-field" placeholder="e.g. IBM SkillsBuild, UniMAP, TechFest">
        </div>
        <div>
            <label for="location" class="label-field">Location / venue</label>
            <input type="text" id="location" name="location" value="{{ old('location', $achievement?->location) }}" class="input-field" placeholder="e.g. Main auditorium, Kuala Lumpur">
        </div>
    </div>

    <div>
        <label for="story" class="label-field">Story * <span class="text-text-dim font-normal">(what happened, what you did, how you earned it)</span></label>
        <textarea id="story" name="story" rows="5" required class="input-field">{{ old('story', $achievement?->story) }}</textarea>
    </div>

    <div>
        <label for="project" class="label-field">Related project / work</label>
        <textarea id="project" name="project" rows="2" class="input-field" placeholder="Optional — link to a project, pitch, or performance">{{ old('project', $achievement?->project) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="issued_date" class="label-field">Date</label>
            <input type="date" id="issued_date" name="issued_date" value="{{ old('issued_date', $achievement?->issued_date?->format('Y-m-d')) }}" class="input-field">
            <p class="mt-1 text-xs text-text-dim">When it was issued, won, or received.</p>
        </div>
        <div>
            <label for="sort_order" class="label-field">Sort order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $achievement?->sort_order ?? 0) }}" min="0" class="input-field">
            <p class="mt-1 text-xs text-text-dim">Lower numbers appear first.</p>
        </div>
    </div>

    <div id="credly-field">
        <label for="credly_url" class="label-field">Credly badge URL <span class="text-text-dim font-normal">(certificates only)</span></label>
        <input type="url" id="credly_url" name="credly_url" value="{{ old('credly_url', $achievement?->credly_url) }}" class="input-field" placeholder="https://www.credly.com/...">
    </div>

    <div class="rounded-xl border border-border bg-surface-muted/40 p-4 space-y-4">
        <p class="text-sm font-semibold text-text">Primary image</p>
        <p class="text-xs text-text-dim">Certificate badge, trophy, medal, competition poster, or event graphic.</p>

        @if($achievement?->badge_image)
        <div class="flex items-center gap-4">
            <img src="{{ $achievement->resolveImageUrl($achievement->badge_image) }}" alt="Current image" class="h-20 w-20 rounded-xl border border-border object-contain bg-surface p-2">
            <label class="flex items-center gap-2 text-sm text-text-muted cursor-pointer">
                <input type="checkbox" name="remove_badge_image" value="1" @checked(old('remove_badge_image')) class="rounded border-border text-uv">
                Remove uploaded image
            </label>
        </div>
        @elseif($achievement?->image_url)
        <div class="flex items-center gap-4">
            <img src="{{ $achievement->image_url }}" alt="Current image URL" class="h-20 w-20 rounded-xl border border-border object-contain bg-surface p-2">
            <p class="text-xs text-text-dim">Currently using external image URL below.</p>
        </div>
        @endif

        <div>
            <label for="badge_image" class="label-field">Upload image</label>
            <input type="file" id="badge_image" name="badge_image" accept="image/jpeg,image/png,image/gif,image/webp" class="input-field file:mr-4 file:rounded-lg file:border-0 file:bg-uv file:px-3 file:py-1.5 file:text-sm file:text-white">
        </div>

        <div>
            <label for="image_url" class="label-field">Or image URL</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $achievement?->image_url) }}" class="input-field" placeholder="https://...">
        </div>
    </div>

    <div class="rounded-xl border border-border bg-surface-muted/40 p-4 space-y-4">
        <p class="text-sm font-semibold text-text">Highlight photo <span class="text-text-dim font-normal">(optional)</span></p>
        <p class="text-xs text-text-dim">On stage, at the podium, receiving the award, or celebrating the win.</p>

        @if($achievement?->award_photo)
        <div class="flex items-center gap-4">
            <img src="{{ $achievement->awardPhotoUrl() }}" alt="Current highlight photo" class="h-24 w-36 rounded-xl border border-border object-cover bg-surface">
            <label class="flex items-center gap-2 text-sm text-text-muted cursor-pointer">
                <input type="checkbox" name="remove_award_photo" value="1" @checked(old('remove_award_photo')) class="rounded border-border text-uv">
                Remove highlight photo
            </label>
        </div>
        @endif

        <div>
            <label for="award_photo" class="label-field">Upload highlight photo</label>
            <input type="file" id="award_photo" name="award_photo" accept="image/jpeg,image/png,image/gif,image/webp" class="input-field file:mr-4 file:rounded-lg file:border-0 file:bg-uv file:px-3 file:py-1.5 file:text-sm file:text-white">
        </div>
    </div>

    <div>
        <label for="skills" class="label-field">Skills / tags <span class="text-text-dim font-normal">(comma-separated)</span></label>
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

@push('scripts')
<script>
(function () {
    const typeSelect = document.getElementById('type');
    const credlyField = document.getElementById('credly-field');
    if (!typeSelect || !credlyField) return;

    const toggleCredly = () => {
        credlyField.classList.toggle('hidden', typeSelect.value !== 'certificate');
    };

    typeSelect.addEventListener('change', toggleCredly);
    toggleCredly();
})();
</script>
@endpush
