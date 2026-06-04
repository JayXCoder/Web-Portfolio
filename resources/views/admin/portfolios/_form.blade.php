@php
$portfolio = $portfolio ?? null;
$categories = config('portfolio-ai.categories', []);
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="card-surface max-w-3xl p-6 space-y-4">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="title" class="label-field">Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $portfolio?->title) }}" required class="input-field">
        </div>
        <div>
            <label for="category" class="label-field">Category *</label>
            <select id="category" name="category" required class="input-field">
                <option value="">Select…</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(old('category', $portfolio?->category) === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="short_description" class="label-field">Short description *</label>
        <textarea id="short_description" name="short_description" rows="2" required class="input-field">{{ old('short_description', $portfolio?->short_description) }}</textarea>
    </div>

    <div>
        <label for="description" class="label-field">Full description *</label>
        <textarea id="description" name="description" rows="6" required class="input-field">{{ old('description', $portfolio?->description) }}</textarea>
    </div>

    <div>
        <label for="technologies" class="label-field">Technologies * <span class="text-text-dim font-normal">(comma-separated)</span></label>
        <input type="text" id="technologies" name="technologies" value="{{ old('technologies', $portfolio?->technologies_string ?? '') }}" required class="input-field">
    </div>

    <div>
        <label for="features" class="label-field">Features * <span class="text-text-dim font-normal">(comma-separated)</span></label>
        <input type="text" id="features" name="features" value="{{ old('features', $portfolio ? implode(', ', $portfolio->features ?? []) : '') }}" required class="input-field">
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="client" class="label-field">Client</label>
            <input type="text" id="client" name="client" value="{{ old('client', $portfolio?->client) }}" class="input-field">
        </div>
        <div>
            <label for="duration_months" class="label-field">Duration (months)</label>
            <input type="number" id="duration_months" name="duration_months" value="{{ old('duration_months', $portfolio?->duration_months) }}" min="1" class="input-field">
        </div>
    </div>

    <div>
        <label for="challenges" class="label-field">Challenges</label>
        <textarea id="challenges" name="challenges" rows="3" class="input-field">{{ old('challenges', $portfolio?->challenges) }}</textarea>
    </div>

    <div>
        <label for="solutions" class="label-field">Solutions</label>
        <textarea id="solutions" name="solutions" rows="3" class="input-field">{{ old('solutions', $portfolio?->solutions) }}</textarea>
    </div>

    @if($portfolio)
    @php
        $orderedImages = old('image_order', $portfolio->images ?? []);
        if (! is_array($orderedImages)) {
            $orderedImages = [];
        }
        $removedImages = old('remove_images', []);
        if (! is_array($removedImages)) {
            $removedImages = [];
        }
        $orderedImages = array_values(array_filter($orderedImages, fn ($path) => is_string($path) && $path !== '' && ! in_array($path, $removedImages, true)));
    @endphp
    @if(count($orderedImages))
    <div id="portfolio-image-manager" class="space-y-2">
        <p class="label-field">Images & order</p>
        <p class="text-xs text-text-dim">Drag to reorder. The first image is the cover on portfolio cards.</p>
        <ul id="portfolio-image-sortable" class="mt-2 flex flex-col gap-2" role="list">
            @foreach($orderedImages as $img)
            <li
                class="portfolio-image-item flex items-center gap-3 rounded-xl border border-border bg-surface-muted p-2"
                draggable="true"
                data-path="{{ $img }}"
            >
                <button type="button" class="portfolio-image-drag shrink-0 cursor-grab px-1 text-text-dim hover:text-text active:cursor-grabbing" aria-label="Drag to reorder" tabindex="-1">⋮⋮</button>
                <img src="{{ $portfolio->imageUrl($img) }}" alt="" class="h-16 w-24 shrink-0 rounded-lg border border-border object-cover" width="96" height="64">
                <span class="portfolio-image-cover-badge badge-uv text-xs {{ $loop->first ? '' : 'hidden' }}">Cover</span>
                <button type="button" class="portfolio-image-remove btn-ghost ml-auto min-h-9 min-w-9 shrink-0 px-2 text-sm text-danger" aria-label="Remove image">Remove</button>
                <input type="hidden" name="image_order[]" value="{{ $img }}">
            </li>
            @endforeach
        </ul>
        <div id="portfolio-image-remove-inputs" class="hidden" aria-hidden="true"></div>
    </div>
    @endif
    @endif

    <div>
        <label for="image_urls" class="label-field">External image URLs <span class="text-text-dim font-normal">(comma-separated, optional)</span></label>
        <input type="text" id="image_urls" name="image_urls" value="{{ old('image_urls', $portfolio && $portfolio->images ? collect($portfolio->images)->filter(fn ($path) => filter_var($path, FILTER_VALIDATE_URL))->implode(', ') : '') }}" class="input-field" placeholder="https://example.com/screenshot.png">
    </div>

    <div>
        <label for="images" class="label-field">Upload images</label>
        <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple class="input-field file:mr-4 file:rounded-lg file:border-0 file:bg-uv file:px-3 file:py-1.5 file:text-sm file:text-white">
        @if($portfolio)
        <p class="mt-1 text-xs text-text-dim">New uploads are appended after your ordered images.</p>
        @endif
    </div>

    <div class="flex flex-wrap gap-6">
        <label class="flex items-center gap-2 cursor-pointer text-sm text-text-muted">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $portfolio?->is_featured)) class="rounded border-border text-uv">
            Featured
        </label>
        <label class="flex items-center gap-2 cursor-pointer text-sm text-text-muted">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $portfolio?->is_published ?? true)) class="rounded border-border text-uv">
            Published
        </label>
    </div>

    <div>
        <label for="sort_order" class="label-field">Sort order</label>
        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $portfolio?->sort_order ?? 0) }}" min="0" class="input-field w-32">
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="btn-primary">Save portfolio</button>
        <a href="{{ route('admin.portfolios') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
