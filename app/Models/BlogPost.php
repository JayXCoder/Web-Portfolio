<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'author_name',
        'cover_image',
        'images',
        'tags',
        'views_count',
        'published_at',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'views_count' => 'integer',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function (BlogPost $post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->orderBy('created_at');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->orderBy('sort_order');
    }

    public function getReadingTimeMinutesAttribute(): int
    {
        $plain = preg_replace('/```[\s\S]*?```/', ' ', $this->body ?? '') ?? '';
        $plain = preg_replace('/[`*_#>\[\]\(\)!|-]/', ' ', $plain) ?? '';
        $words = str_word_count(trim($plain));

        return max(1, (int) ceil($words / 200));
    }

    public function getSeoDescriptionAttribute(): string
    {
        if (filled($this->excerpt)) {
            return Str::limit(trim($this->excerpt), 160, '');
        }

        $plain = preg_replace('/```[\s\S]*?```/', ' ', $this->body ?? '') ?? '';
        $plain = preg_replace('/[#>*_`\[\]]/', ' ', $plain) ?? '';
        $plain = trim(preg_replace('/\s+/', ' ', $plain) ?? '');

        return Str::limit($plain, 160, '');
    }

    public function coverImageUrl(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }

        $filename = basename($this->cover_image);

        return route('blog.image', ['filename' => $filename]);
    }

    public function absoluteCoverImageUrl(): ?string
    {
        $url = $this->coverImageUrl();

        return $url ? url($url) : null;
    }
}
