<?php

namespace App\Services;

use App\Jobs\RefreshKnowledgeSource;
use App\Models\BlogPost;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostService
{
    public function __construct(
        private BlogPostRepositoryInterface $blogPostRepository
    ) {}

    public function getAllPublished(): Collection
    {
        return $this->blogPostRepository->getAllPublished();
    }

    public function getPublishedBySlug(string $slug): ?BlogPost
    {
        return $this->blogPostRepository->getPublishedBySlug($slug);
    }

    public function getAllForAdmin(): Collection
    {
        return $this->blogPostRepository->getAllForAdmin();
    }

    public function createPost(array $data): BlogPost
    {
        $data = $this->normalizePostData($data);
        $data['slug'] = $this->generateUniqueSlug($data['title'], $data['slug'] ?? null);
        $data['images'] = $data['images'] ?? [];

        if (isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            $data['cover_image'] = $this->storeImage($data['cover_image']);
        }

        $post = $this->blogPostRepository->create($data);
        $this->maybeQueueReindex($post, wasPublished: false);

        return $post;
    }

    public function updatePost(BlogPost $post, array $data): BlogPost
    {
        $wasPublished = (bool) $post->is_published;
        $data = $this->normalizePostData($data, $post);

        if (! empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'] ?? $post->title, $data['slug'], $post->id);
        } else {
            unset($data['slug']);
        }

        if (isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            $this->deleteStoredFile($post->cover_image);
            $data['cover_image'] = $this->storeImage($data['cover_image']);
        } elseif (! empty($data['remove_cover'])) {
            $this->deleteStoredFile($post->cover_image);
            $data['cover_image'] = null;
        } else {
            unset($data['cover_image']);
        }
        unset($data['remove_cover']);

        $images = $post->images ?? [];
        if (! empty($data['new_images']) && is_array($data['new_images'])) {
            foreach ($data['new_images'] as $file) {
                if ($file instanceof UploadedFile) {
                    $images[] = $this->storeImage($file);
                }
            }
        }
        unset($data['new_images']);

        if (! empty($data['remove_images']) && is_array($data['remove_images'])) {
            foreach ($data['remove_images'] as $path) {
                $this->deleteStoredFile($path);
                $images = array_values(array_filter($images, fn ($img) => $img !== $path));
            }
        }
        unset($data['remove_images']);

        $data['images'] = array_values($images);

        $post = $this->blogPostRepository->update($post, $data);
        $this->maybeQueueReindex($post, $wasPublished);

        return $post;
    }

    public function deletePost(BlogPost $post): bool
    {
        $wasPublished = (bool) $post->is_published;
        $this->deleteStoredFile($post->cover_image);
        foreach ($post->images ?? [] as $image) {
            $this->deleteStoredFile($image);
        }

        $deleted = $this->blogPostRepository->delete($post);

        if ($deleted && $wasPublished) {
            RefreshKnowledgeSource::dispatch('blog');
        }

        return $deleted;
    }

    public function recordView(BlogPost $post, Request $request): void
    {
        if (! $post->is_published) {
            return;
        }

        $key = 'blog_viewed:'.$post->id;
        if ($request->session()->has($key)) {
            return;
        }

        $this->blogPostRepository->incrementViews($post);
        $request->session()->put($key, true);
        $post->refresh();
    }

    public function uploadInlineImage(UploadedFile $file, ?BlogPost $post = null): array
    {
        $path = $this->storeImage($file);
        $filename = basename($path);
        $url = route('blog.image', ['filename' => $filename]);

        if ($post) {
            $images = $post->images ?? [];
            $images[] = $path;
            $this->blogPostRepository->update($post, ['images' => array_values(array_unique($images))]);
        }

        return [
            'path' => $path,
            'url' => $url,
            'markdown' => '![]('.$url.')',
        ];
    }

    private function normalizePostData(array $data, ?BlogPost $post = null): array
    {
        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_values(array_filter(array_map('trim', explode(',', $data['tags']))));
        }

        if (array_key_exists('is_published', $data)) {
            $data['is_published'] = filter_var($data['is_published'], FILTER_VALIDATE_BOOLEAN);
        }

        if (! empty($data['is_published']) && empty($data['published_at']) && (! $post || ! $post->published_at)) {
            $data['published_at'] = now();
        }

        if (isset($data['sort_order'])) {
            $data['sort_order'] = (int) $data['sort_order'];
        }

        return $data;
    }

    private function maybeQueueReindex(BlogPost $post, bool $wasPublished): void
    {
        if ($post->is_published || $wasPublished) {
            RefreshKnowledgeSource::dispatch('blog');
        }
    }

    private function generateUniqueSlug(string $title, ?string $preferred = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($preferred ?: $title) ?: 'post';
        $slug = $base;
        $i = 1;

        while (
            BlogPost::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    private function storeImage(UploadedFile $file): string
    {
        return $file->store('blog', 'public');
    }

    private function deleteStoredFile(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
