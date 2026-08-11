<?php

namespace App\Services;

use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Repositories\Interfaces\BlogCommentRepositoryInterface;
use App\Support\CommentSanitizer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BlogCommentService
{
    public function __construct(
        private BlogCommentRepositoryInterface $blogCommentRepository
    ) {}

    public function getAllForAdmin(?int $blogPostId = null): Collection
    {
        return $this->blogCommentRepository->getAllForAdmin($blogPostId);
    }

    public function getForPost(BlogPost $post): Collection
    {
        return $this->blogCommentRepository->getForPost($post);
    }

    public function createComment(BlogPost $post, array $data, Request $request): BlogComment
    {
        return $this->blogCommentRepository->create([
            'blog_post_id' => $post->id,
            'author_name' => CommentSanitizer::authorName((string) ($data['author_name'] ?? '')),
            'author_email' => CommentSanitizer::email($data['author_email'] ?? null),
            'body' => CommentSanitizer::body((string) ($data['body'] ?? '')),
            'ip_address' => $request->ip(),
        ]);
    }

    public function deleteComment(BlogComment $comment): bool
    {
        return $this->blogCommentRepository->delete($comment);
    }

    /** @param  list<int>  $ids */
    public function bulkDelete(array $ids): int
    {
        return $this->blogCommentRepository->deleteByIds($ids);
    }
}
