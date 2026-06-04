<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'technologies',
        'category',
        'images',
        'features',
        'duration_months',
        'client',
        'challenges',
        'solutions',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'technologies' => 'array',
        'images' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'duration_months' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portfolio) {
            if (empty($portfolio->slug)) {
                $portfolio->slug = Str::slug($portfolio->title);
            }
        });

        static::updating(function ($portfolio) {
            if ($portfolio->isDirty('title') && empty($portfolio->slug)) {
                $portfolio->slug = Str::slug($portfolio->title);
            }
        });
    }

    /**
     * Scope to get published portfolios
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get featured portfolios
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get portfolios by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to order portfolios
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the main image (first image from array)
     */
    public function getMainImageAttribute(): ?string
    {
        if (! is_array($this->images) || $this->images === []) {
            return null;
        }

        return $this->images[0] ?? null;
    }

    /**
     * Public URL for a stored path or external image URL.
     */
    public function imageUrl(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return route('portfolio.image', ['filename' => basename($path)]);
    }

    /**
     * Blade-friendly alias (snake_case is not auto-mapped to imageUrl).
     */
    public function image_url(?string $path): ?string
    {
        return $this->imageUrl($path);
    }

    /**
     * Get technologies as comma-separated string
     */
    public function getTechnologiesStringAttribute(): string
    {
        return $this->technologies ? implode(', ', $this->technologies) : '';
    }
}
