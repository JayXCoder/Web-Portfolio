@props([
    'slug' => null,
    'color' => 'a855f7',
    'label' => '',
    'icon' => null,
])

@php
    $slug = $slug ? strtolower(trim((string) $slug)) : null;
    $color = ltrim((string) $color, '#');
    $primary = $icon ?: ($slug ? "https://cdn.simpleicons.org/{$slug}/{$color}" : null);
    $fallback = $slug ? 'https://cdn.jsdelivr.net/npm/simple-icons@11.15.0/icons/'.$slug.'.svg' : null;
@endphp

<span {{ $attributes->merge(['class' => 'skill-icon-wrap']) }} aria-hidden="true">
    @if ($primary)
        <img
            src="{{ $primary }}"
            @if ($fallback && ! $icon) data-fallback="{{ $fallback }}" @endif
            alt=""
            width="22"
            height="22"
            class="skill-icon-img"
            loading="lazy"
            decoding="async"
            onerror="window.__skillIconFallback && window.__skillIconFallback(this)"
        >
    @else
        <svg class="skill-icon-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>
        </svg>
    @endif
</span>
