<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KnowledgeAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_an_admin_can_open_the_knowledge_console(): void
    {
        Http::fake(['*/api/tags' => Http::response(['models' => [
            ['name' => 'qwen3.5:2b'],
            ['name' => 'qwen3-embedding:0.6b'],
        ]])]);
        $viewer = User::factory()->create(['role' => 'viewer', 'is_active' => true]);
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($viewer)->get('/admin/knowledge')->assertRedirect();
        $this->actingAs($admin)->get('/admin/knowledge')
            ->assertOk()
            ->assertSee('RAG control room')
            ->assertSee('qwen3.5:2b');
    }
}
