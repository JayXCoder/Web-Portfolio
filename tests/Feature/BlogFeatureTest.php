<?php

namespace Tests\Feature;

use App\Jobs\RefreshKnowledgeSource;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_hides_drafts(): void
    {
        BlogPost::create($this->postAttrs(['title' => 'Live', 'slug' => 'live', 'is_published' => true]));
        BlogPost::create($this->postAttrs(['title' => 'Draft', 'slug' => 'draft', 'is_published' => false]));

        $this->get(route('blog'))
            ->assertOk()
            ->assertSee('Live')
            ->assertDontSee('Draft');
    }

    public function test_published_post_shows_meta_share_and_og_tags(): void
    {
        $post = BlogPost::create($this->postAttrs([
            'title' => 'OG Post',
            'slug' => 'og-post',
            'excerpt' => 'A short excerpt for social cards.',
            'author_name' => 'Jay',
            'is_published' => true,
            'published_at' => now(),
            'cover_image' => 'blog/cover.png',
        ]));

        $this->get(route('blog.show', $post->slug))
            ->assertOk()
            ->assertSee('OG Post', false)
            ->assertSee('Jay', false)
            ->assertSee('min read', false)
            ->assertSee('views', false)
            ->assertSee('data-blog-share', false)
            ->assertSee('property="og:type" content="article"', false)
            ->assertSee('property="og:title" content="OG Post"', false)
            ->assertSee('property="og:description" content="A short excerpt for social cards."', false)
            ->assertSee('property="og:image"', false);
    }

    public function test_view_count_increments_once_per_session(): void
    {
        $post = BlogPost::create($this->postAttrs([
            'title' => 'Views',
            'slug' => 'views',
            'is_published' => true,
            'published_at' => now(),
        ]));

        $this->get(route('blog.show', 'views'))->assertOk();
        $this->assertSame(1, $post->fresh()->views_count);

        $this->get(route('blog.show', 'views'))->assertOk();
        $this->assertSame(1, $post->fresh()->views_count);
    }

    public function test_comment_appears_immediately_and_admin_can_delete(): void
    {
        $post = BlogPost::create($this->postAttrs([
            'title' => 'Discuss',
            'slug' => 'discuss',
            'is_published' => true,
            'published_at' => now(),
        ]));

        $this->post(route('blog.comments.store', 'discuss'), [
            'author_name' => 'Reader',
            'author_email' => 'r@example.com',
            'body' => 'Nice post!',
            'website' => '',
        ])->assertRedirect();

        $this->get(route('blog.show', 'discuss'))
            ->assertOk()
            ->assertSee('Nice post!')
            ->assertSee('Reader');

        $comment = BlogComment::first();
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->delete(route('admin.blog-comments.delete', $comment))
            ->assertRedirect(route('admin.blog-comments'));

        $this->assertDatabaseMissing('blog_comments', ['id' => $comment->id]);
        $this->get(route('blog.show', 'discuss'))->assertDontSee('Nice post!');
    }

    public function test_comment_html_and_scripts_are_stripped_and_escaped_on_page(): void
    {
        $post = BlogPost::create($this->postAttrs([
            'title' => 'Safe',
            'slug' => 'safe-comments',
            'is_published' => true,
            'published_at' => now(),
        ]));

        $this->post(route('blog.comments.store', 'safe-comments'), [
            'author_name' => '<img src=x onerror=alert(1)>Jay',
            'author_email' => 'safe@example.com',
            'body' => '<script>alert("xss")</script>Hello & welcome <b>friend</b>',
            'website' => '',
        ])->assertRedirect();

        $comment = BlogComment::first();
        $this->assertNotNull($comment);
        $this->assertSame('Jay', $comment->author_name);
        $this->assertSame('Hello & welcome friend', $comment->body);
        $this->assertStringNotContainsString('<script', $comment->body);
        $this->assertStringNotContainsString('<b>', $comment->body);

        $this->get(route('blog.show', 'safe-comments'))
            ->assertOk()
            ->assertSee('Hello &amp; welcome friend', false)
            ->assertDontSee('<script>', false)
            ->assertDontSee('<img', false)
            ->assertDontSee('onerror=', false);
    }

    public function test_honeypot_comments_are_silently_discarded(): void
    {
        $post = BlogPost::create($this->postAttrs([
            'title' => 'Bot Trap',
            'slug' => 'bot-trap',
            'is_published' => true,
            'published_at' => now(),
        ]));

        $this->post(route('blog.comments.store', 'bot-trap'), [
            'author_name' => 'Spam Bot',
            'author_email' => 'bot@spam.test',
            'body' => 'Buy followers now',
            'website' => 'https://spam.example',
        ])
            ->assertRedirect(route('blog.show', 'bot-trap').'#comments')
            ->assertSessionHas('success');

        $this->assertDatabaseCount('blog_comments', 0);
        $this->get(route('blog.show', 'bot-trap'))->assertDontSee('Buy followers now');
    }

    public function test_blank_markup_only_comment_is_rejected(): void
    {
        $post = BlogPost::create($this->postAttrs([
            'title' => 'Empty Markup',
            'slug' => 'empty-markup',
            'is_published' => true,
            'published_at' => now(),
        ]));

        $this->from(route('blog.show', 'empty-markup'))
            ->post(route('blog.comments.store', 'empty-markup'), [
                'author_name' => '<script></script>',
                'body' => '<script></script><b></b>',
                'website' => '',
            ])
            ->assertRedirect(route('blog.show', 'empty-markup'))
            ->assertSessionHasErrors(['author_name', 'body']);

        $this->assertDatabaseCount('blog_comments', 0);
    }

    public function test_publishing_post_dispatches_blog_reindex(): void
    {
        Queue::fake();
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->post(route('admin.blog-posts.store'), [
                'title' => 'Published Knowledge',
                'body' => '# Hello RAG',
                'author_name' => 'Jay',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.blog-posts'));

        Queue::assertPushed(RefreshKnowledgeSource::class, function (RefreshKnowledgeSource $job) {
            return $job->source === 'blog';
        });
    }

    public function test_admin_can_upload_inline_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.blog-posts.upload-image'), [
                'image' => UploadedFile::fake()->image('shot.jpg'),
            ]);

        $response->assertOk()
            ->assertJsonStructure(['path', 'url', 'markdown']);

        $this->assertStringContainsString('![](', $response->json('markdown'));
    }

    /** @param  array<string, mixed>  $overrides */
    private function postAttrs(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Post',
            'slug' => 'post-'.uniqid(),
            'excerpt' => null,
            'body' => "## Body\n\nSome words here for reading time.",
            'author_name' => 'Jay',
            'cover_image' => null,
            'images' => [],
            'tags' => ['tech'],
            'views_count' => 0,
            'published_at' => null,
            'is_published' => false,
            'sort_order' => 0,
        ], $overrides);
    }
}
