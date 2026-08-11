@php
    $isEdit = isset($post) && $post;
    $uploadUrl = route('admin.blog-posts.upload-image');
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label for="title" class="label-field">Title</label>
        <input id="title" name="title" type="text" required maxlength="255" class="input-field mt-1" value="{{ old('title', $post->title ?? '') }}">
        @error('title')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="slug" class="label-field">Slug <span class="font-normal text-text-dim">(optional)</span></label>
        <input id="slug" name="slug" type="text" maxlength="255" class="input-field mt-1" value="{{ old('slug', $post->slug ?? '') }}" placeholder="auto-from-title">
        @error('slug')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label for="author_name" class="label-field">Author</label>
        <input id="author_name" name="author_name" type="text" required maxlength="120" class="input-field mt-1" value="{{ old('author_name', $post->author_name ?? $defaultAuthor) }}">
        @error('author_name')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="tags" class="label-field">Tags <span class="font-normal text-text-dim">(comma-separated)</span></label>
        <input id="tags" name="tags" type="text" class="input-field mt-1" value="{{ old('tags', isset($post) && $post->tags ? implode(', ', $post->tags) : '') }}" placeholder="laravel, rag, devops">
        @error('tags')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label for="excerpt" class="label-field">Excerpt</label>
    <textarea id="excerpt" name="excerpt" rows="2" class="input-field mt-1" maxlength="2000">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    @error('excerpt')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label for="cover_image" class="label-field">Cover image</label>
        <p class="mt-1 text-xs text-text-dim">Recommended ~1200×630 for X / WhatsApp link previews.</p>
        <input id="cover_image" name="cover_image" type="file" accept="image/*" class="input-field mt-2">
        @if($isEdit && $post->coverImageUrl())
            <div class="mt-3 flex items-start gap-3">
                <img src="{{ $post->coverImageUrl() }}" alt="" class="h-20 w-32 object-cover">
                <label class="inline-flex items-center gap-2 text-sm text-text-muted">
                    <input type="checkbox" name="remove_cover" value="1" class="rounded border-border text-uv focus:ring-uv">
                    Remove cover
                </label>
            </div>
        @endif
        @error('cover_image')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
    </div>
    <div class="space-y-3">
        <label class="inline-flex items-center gap-2 text-sm text-text cursor-pointer">
            <input type="checkbox" name="is_published" value="1" class="rounded border-border text-uv focus:ring-uv" @checked(old('is_published', $post->is_published ?? false))>
            Published
        </label>
        <div>
            <label for="published_at" class="label-field">Published at</label>
            <input id="published_at" name="published_at" type="datetime-local" class="input-field mt-1"
                   value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div>
            <label for="sort_order" class="label-field">Sort order</label>
            <input id="sort_order" name="sort_order" type="number" min="0" class="input-field mt-1" value="{{ old('sort_order', $post->sort_order ?? 0) }}">
        </div>
        @if($isEdit)
            <p class="text-xs text-text-dim">Views: {{ number_format($post->views_count) }} (read-only)</p>
        @endif
    </div>
</div>

<div>
    <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
        <label for="blog-body" class="label-field mb-0">Body (Markdown)</label>
        <div class="flex items-center gap-3">
            <span id="blog-read-time" class="text-xs text-text-dim">1 min read</span>
            <button
                type="button"
                id="blog-inline-image-btn"
                class="btn-secondary min-h-9 py-1 text-xs"
                data-upload-url="{{ $uploadUrl }}"
            >
                Insert image
            </button>
            <input type="file" id="blog-inline-image-input" accept="image/*" class="hidden"
                   @if($isEdit) data-post-id="{{ $post->id }}" @endif>
        </div>
    </div>

    <div class="blog-admin-split">
        <textarea
            id="blog-body"
            name="body"
            required
            class="input-field"
            placeholder="# Heading&#10;&#10;Write Markdown here. Use ```lang for code, ```mermaid for diagrams."
        >{{ old('body', $post->body ?? '') }}</textarea>
        <div id="blog-md-preview" class="blog-admin-preview-pane md-preview" data-md-enhance aria-live="polite"></div>
    </div>
    @error('body')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
</div>

@if($isEdit && !empty($post->images))
    <div>
        <p class="label-field">Uploaded inline images</p>
        <ul class="mt-2 space-y-2 text-sm text-text-muted">
            @foreach($post->images as $image)
                <li class="flex items-center gap-3">
                    <code class="text-xs text-text-dim">{{ $image }}</code>
                    <label class="inline-flex items-center gap-1 text-xs">
                        <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="rounded border-border text-uv">
                        Remove
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="flex flex-wrap gap-3">
    <button type="submit" class="btn-primary">{{ $isEdit ? 'Save changes' : 'Create post' }}</button>
    <a href="{{ route('admin.blog-posts') }}" class="btn-secondary">Cancel</a>
</div>
