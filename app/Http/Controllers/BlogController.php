<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogCommentRequest;
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

    public function storeComment(StoreBlogCommentRequest $request, string $slug): RedirectResponse
    {
        $post = $this->blogPostService->getPublishedBySlug($slug);
        abort_if(! $post, 404);

        // Silent success for bots that fill the honeypot — do not store anything.
        if (! $request->isHoneypotTriggered()) {
            $this->blogCommentService->createComment($post, $request->safe()->only([
                'author_name',
                'author_email',
                'body',
            ]), $request);
        }

        return redirect()
            ->route('blog.show', $post)
            ->withFragment('comments')
            ->with('success', 'Comment posted.');
    }
}
