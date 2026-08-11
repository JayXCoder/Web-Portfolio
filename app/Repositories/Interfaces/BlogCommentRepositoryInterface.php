<?php

namespace App\Repositories\Interfaces;

use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Collection;

interface BlogCommentRepositoryInterface
{
    public function create(array $data): BlogComment;

    public function getAllForAdmin(?int $blogPostId = null): Collection;

    public function delete(BlogComment $comment): bool;

    /** @param  list<int>  $ids */
    public function deleteByIds(array $ids): int;

    public function getForPost(BlogPost $post): Collection;
}
