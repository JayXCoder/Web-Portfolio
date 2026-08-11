<?php

namespace App\Repositories;

use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Repositories\Interfaces\BlogCommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BlogCommentRepository implements BlogCommentRepositoryInterface
{
    public function __construct(
        private BlogComment $model
    ) {}

    public function create(array $data): BlogComment
    {
        return $this->model->create($data);
    }

    public function getAllForAdmin(?int $blogPostId = null): Collection
    {
        $query = $this->model
            ->with('post:id,title,slug')
            ->orderByDesc('created_at');

        if ($blogPostId !== null) {
            $query->where('blog_post_id', $blogPostId);
        }

        return $query->get();
    }

    public function delete(BlogComment $comment): bool
    {
        return (bool) $comment->delete();
    }

    public function deleteByIds(array $ids): int
    {
        if ($ids === []) {
            return 0;
        }

        return $this->model->whereIn('id', $ids)->delete();
    }

    public function getForPost(BlogPost $post): Collection
    {
        return $this->model
            ->where('blog_post_id', $post->id)
            ->orderBy('created_at')
            ->get();
    }
}
