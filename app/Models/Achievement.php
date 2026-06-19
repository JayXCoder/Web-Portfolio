<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'organization',
        'location',
        'title',
        'placement',
        'story',
        'project',
        'issued_date',
        'credly_url',
        'image_url',
        'badge_image',
        'award_photo',
        'skills',
        'sort_order',
        'is_published',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'skills' => 'array',
        'issued_date' => 'date',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('issued_date', 'desc');
    }

    public function getSkillsStringAttribute(): string
    {
        return $this->skills ? implode(', ', $this->skills) : '';
    }

    /**
     * @return array<string, mixed>
     */
    public function typeConfig(): array
    {
        $types = config('achievements.types', []);

        return $types[$this->type] ?? $types['certificate'] ?? [];
    }

    public function typeLabel(): string
    {
        return $this->typeConfig()['label'] ?? 'Achievement';
    }

    public function showsCredly(): bool
    {
        return (bool) ($this->typeConfig()['show_credly'] ?? false);
    }

    public function badgeUrl(): ?string
    {
        return $this->resolveImageUrl($this->badge_image) ?? $this->resolveImageUrl($this->image_url);
    }

    public function awardPhotoUrl(): ?string
    {
        return $this->resolveImageUrl($this->award_photo);
    }

    public function resolveImageUrl(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        if (str_starts_with($path, 'achievement-badges/')) {
            return route('achievement.badge', basename($path));
        }

        if (str_starts_with($path, 'achievement-photos/')) {
            return route('achievement.photo', basename($path));
        }

        return route('achievement.badge', basename($path));
    }
}
