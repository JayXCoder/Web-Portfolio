@props(['title', 'description' => null])
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h2 class="font-display text-2xl font-bold text-text sm:text-3xl">{{ $title }}</h2>
        @if($description)
            <p class="mt-1 text-sm text-text-muted">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex flex-wrap gap-2">{{ $actions }}</div>
    @endif
</div>
