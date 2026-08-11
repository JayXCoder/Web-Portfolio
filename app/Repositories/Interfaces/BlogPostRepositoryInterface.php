<?php

namespace App\Repositories\Interfaces;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Collection;

interface BlogPostRepositoryInterface
{
    public function getAllPublished(): Collection;

    public function getBySlug(string $slug): ?BlogPost;

    public function getPublishedBySlug(string $slug): ?BlogPost;

    public function create(array $data): BlogPost;

    public function update(BlogPost $post, array $data): BlogPost;

    public function delete(BlogPost $post): bool;

    public function getAllForAdmin(): Collection;

    public function incrementViews(BlogPost $post): void;
}
