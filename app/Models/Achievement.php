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
        'organization',
        'title',
        'story',
        'project',
        'issued_date',
        'credly_url',
        'image_url',
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

    public function badgeUrl(): ?string
    {
        $url = $this->image_url;

        if ($url === null || trim($url) === '') {
            return null;
        }

        return trim($url);
    }
}
