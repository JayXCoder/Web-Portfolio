<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Services\BlogPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBlogController extends Controller
{
    public function __construct(
        private BlogPostService $blogPostService
    ) {}

    public function index(): View
    {
        $posts = $this->blogPostService->getAllForAdmin();

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.blog-posts.create', [
            'defaultAuthor' => auth()->user()?->name ?? 'Jay',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $post = $this->blogPostService->createPost($data);

        $message = 'Blog post created.';
        if ($post->is_published) {
            $message .= ' Blog knowledge reindex queued.';
        }

        return redirect()->route('admin.blog-posts')->with('success', $message);
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.blog-posts.edit', ['post' => $blogPost]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $data = $this->validated($request, $blogPost);
        $post = $this->blogPostService->updatePost($blogPost, $data);

        $message = 'Blog post updated.';
        if ($post->is_published) {
            $message .= ' Blog knowledge reindex queued.';
        }

        return redirect()->route('admin.blog-posts')->with('success', $message);
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $this->blogPostService->deletePost($blogPost);

        return redirect()->route('admin.blog-posts')->with('success', 'Blog post deleted.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'blog_post_id' => 'nullable|exists:blog_posts,id',
        ]);

        $post = null;
        if ($request->filled('blog_post_id')) {
            $post = BlogPost::find($request->integer('blog_post_id'));
        }

        $result = $this->blogPostService->uploadInlineImage($request->file('image'), $post);

        return response()->json($result);
    }

    private function validated(Request $request, ?BlogPost $post = null): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:2000',
            'body' => 'required|string',
            'author_name' => 'required|string|max:120',
            'tags' => 'nullable|string|max:1000',
            'cover_image' => 'nullable|image|max:5120',
            'remove_cover' => 'nullable|boolean',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|max:5120',
            'is_published' => 'nullable',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['remove_cover'] = $request->boolean('remove_cover');

        return $data;
    }
}
