@props([
    'text' => '',
    'class' => 'text-text-muted leading-relaxed space-y-3',
])

@if(trim((string) $text) !== '')
<div {{ $attributes->merge(['class' => $class]) }}>
    {!! \App\Support\MultilineText::toHtml($text) !!}
</div>
@endif
