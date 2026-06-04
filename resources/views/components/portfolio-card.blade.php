@props(['item'])

<article {{ $attributes->merge(['class' => 'card-surface-hover fade-in-view overflow-hidden flex flex-col']) }} data-category="{{ $item->category }}">
    <div class="aspect-video bg-surface-muted">
        @if($item->main_image)
            <img src="{{ $item->image_url($item->main_image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover" loading="lazy" width="640" height="360">
        @else
            <div class="flex h-full items-center justify-center text-text-dim text-sm">No image</div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-5">
        <span class="badge-uv mb-2 w-fit">{{ $item->category }}</span>
        <h2 class="font-display text-lg font-semibold text-text">{{ $item->title }}</h2>
        <p class="mt-2 flex-1 text-sm text-text-muted line-clamp-3">{{ $item->short_description }}</p>
        <a href="{{ route('portfolio.item', $item->slug) }}" class="btn-primary mt-4 w-full text-center">View project</a>
    </div>
</article>
