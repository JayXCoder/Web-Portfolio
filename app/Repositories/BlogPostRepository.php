<?php

namespace App\Repositories;

use App\Models\BlogPost;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BlogPostRepository implements BlogPostRepositoryInterface
{
    public function __construct(
        private BlogPost $model
    ) {}

    public function getAllPublished(): Collection
    {
        return $this->model
            ->published()
            ->ordered()
            ->withCount('comments')
            ->get();
    }

    public function getBySlug(string $slug): ?BlogPost
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getPublishedBySlug(string $slug): ?BlogPost
    {
        return $this->model
            ->published()
            ->where('slug', $slug)
            ->with(['comments' => fn ($q) => $q->orderBy('created_at')])
            ->first();
    }

    public function create(array $data): BlogPost
    {
        return $this->model->create($data);
    }

    public function update(BlogPost $post, array $data): BlogPost
    {
        $post->update($data);

        return $post->fresh();
    }

    public function delete(BlogPost $post): bool
    {
        return (bool) $post->delete();
    }

    public function getAllForAdmin(): Collection
    {
        return $this->model
            ->withCount('comments')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function incrementViews(BlogPost $post): void
    {
        $post->increment('views_count');
    }
}
