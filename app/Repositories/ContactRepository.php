<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository implements ContactRepositoryInterface
{
    public function __construct(
        private Contact $model
    ) {}

    /**
     * Create a new contact
     */
    public function create(array $data): Contact
    {
        return $this->model->create($data);
    }

    /**
     * Get all contacts for admin
     */
    public function getAllForAdmin(): Collection
    {
        return $this->model
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get unread contacts
     */
    public function getUnread(): Collection
    {
        return $this->model
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get read contacts
     */
    public function getRead(): Collection
    {
        return $this->model
            ->read()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mark contact as read
     */
    public function markAsRead(Contact $contact): Contact
    {
        $contact->markAsRead();
        return $contact;
    }

    /**
     * Delete a contact
     */
    public function delete(Contact $contact): bool
    {
        return $contact->delete();
    }
}
