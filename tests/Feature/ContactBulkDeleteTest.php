<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactBulkDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_bulk_delete_selected_contacts(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $keep = Contact::create([
            'name' => 'Keep Me',
            'email' => 'keep@example.com',
            'message' => 'Please keep this message.',
            'is_read' => false,
        ]);
        $removeA = Contact::create([
            'name' => 'Remove A',
            'email' => 'a@example.com',
            'message' => 'Delete me.',
            'is_read' => true,
        ]);
        $removeB = Contact::create([
            'name' => 'Remove B',
            'email' => 'b@example.com',
            'message' => 'Delete me too.',
            'is_read' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.contacts.bulk-delete'), ['ids' => [$removeA->id, $removeB->id]])
            ->assertRedirect(route('admin.contacts'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', ['id' => $keep->id]);
        $this->assertDatabaseMissing('contacts', ['id' => $removeA->id]);
        $this->assertDatabaseMissing('contacts', ['id' => $removeB->id]);
    }

    public function test_bulk_delete_requires_at_least_one_id(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->from(route('admin.contacts'))
            ->delete(route('admin.contacts.bulk-delete'), ['ids' => []])
            ->assertRedirect(route('admin.contacts'))
            ->assertSessionHasErrors('ids');
    }
}
