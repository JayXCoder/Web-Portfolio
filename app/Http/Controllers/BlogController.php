<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Services\BlogCommentService;
use App\Services\BlogPostService;
use App\Support\MarkdownRenderer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private BlogPostService $blogPostService,
        private BlogCommentService $blogCommentService
    ) {}

    public function index(): View
    {
        $posts = $this->blogPostService->getAllPublished();

        return view('pages.blog', compact('posts'));
    }

    public function show(Request $request, string $slug): View
    {
        $post = $this->blogPostService->getPublishedBySlug($slug);
        abort_if(! $post, 404);

        $this->blogPostService->recordView($post, $request);
        $html = MarkdownRenderer::toHtml($post->body);
        $comments = $post->comments;

        return view('pages.blog-post', compact('post', 'html', 'comments'));
    }

    public function storeComment(Request $request, string $slug): RedirectResponse
    {
        $post = $this->blogPostService->getPublishedBySlug($slug);
        abort_if(! $post, 404);

        $validated = $request->validate([
            'author_name' => 'required|string|max:120',
            'author_email' => 'nullable|email|max:255',
            'body' => 'required|string|max:5000',
        ]);

        $this->blogCommentService->createComment($post, $validated, $request);

        return redirect()
            ->route('blog.show', $post)
            ->withFragment('comments')
            ->with('success', 'Comment posted.');
    }
}
