<?php

namespace App\Repositories\Interfaces;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepositoryInterface
{
    /**
     * Create a new contact
     */
    public function create(array $data): Contact;

    /**
     * Get all contacts for admin
     */
    public function getAllForAdmin(): Collection;

    /**
     * Get unread contacts
     */
    public function getUnread(): Collection;

    /**
     * Get read contacts
     */
    public function getRead(): Collection;

    /**
     * Mark contact as read
     */
    public function markAsRead(Contact $contact): Contact;

    /**
     * Delete a contact
     */
    public function delete(Contact $contact): bool;
}
