<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use App\Services\BlogCommentService;
use App\Services\BlogPostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBlogCommentController extends Controller
{
    public function __construct(
        private BlogCommentService $blogCommentService,
        private BlogPostService $blogPostService
    ) {}

    public function index(Request $request): View
    {
        $postId = $request->filled('post') ? (int) $request->input('post') : null;
        $comments = $this->blogCommentService->getAllForAdmin($postId);
        $posts = $this->blogPostService->getAllForAdmin();

        return view('admin.blog-comments.index', compact('comments', 'posts', 'postId'));
    }

    public function show(BlogComment $blogComment): View
    {
        $blogComment->load('post');

        return view('admin.blog-comments.show', ['comment' => $blogComment]);
    }

    public function destroy(BlogComment $blogComment): RedirectResponse
    {
        $this->blogCommentService->deleteComment($blogComment);

        return redirect()->route('admin.blog-comments')->with('success', 'Comment deleted.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:blog_comments,id',
        ]);

        $count = $this->blogCommentService->bulkDelete($validated['ids']);

        return redirect()
            ->route('admin.blog-comments')
            ->with('success', $count.' comment(s) deleted.');
    }
}
