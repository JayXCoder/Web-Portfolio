@props([
    'slug' => null,
    'color' => 'a855f7',
    'label' => '',
])

@php
    $slug = $slug ? strtolower(trim((string) $slug)) : null;
    $color = ltrim((string) $color, '#');
@endphp

<span {{ $attributes->merge(['class' => 'skill-icon-wrap']) }} aria-hidden="true">
    @if ($slug)
        <img
            src="https://cdn.simpleicons.org/{{ $slug }}/{{ $color }}"
            alt=""
            width="22"
            height="22"
            class="skill-icon-img"
            loading="lazy"
            decoding="async"
        >
    @else
        <svg class="skill-icon-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>
        </svg>
    @endif
</span>
