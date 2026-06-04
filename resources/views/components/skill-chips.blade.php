@props(['skills' => [], 'dense' => false])

<ul @class([
    'skill-chip-grid',
    'skill-chip-grid--dense' => $dense,
])>
    @foreach ($skills as $skill)
    <li class="skill-chip">
        <x-skill-icon
            :slug="$skill['slug'] ?? null"
            :color="$skill['color'] ?? 'a855f7'"
            :icon="$skill['icon'] ?? null"
            :label="$skill['label']"
        />
        <span class="skill-chip-label">{{ $skill['label'] }}</span>
    </li>
    @endforeach
</ul>
