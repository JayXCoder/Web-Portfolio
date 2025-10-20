<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ContactService
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository
    ) {}

    /**
     * Create a new contact submission
     */
    public function createContact(array $data): Contact
    {
        $contact = $this->contactRepository->create($data);

        // Send notification to admin (optional)
        $this->notifyAdmin($contact);

        return $contact;
    }

    /**
     * Get all contacts for admin
     */
    public function getAllForAdmin(): Collection
    {
        return $this->contactRepository->getAllForAdmin();
    }

    /**
     * Get unread contacts
     */
    public function getUnread(): Collection
    {
        return $this->contactRepository->getUnread();
    }

    /**
     * Get read contacts
     */
    public function getRead(): Collection
    {
        return $this->contactRepository->getRead();
    }

    /**
     * Mark contact as read
     */
    public function markAsRead(Contact $contact): Contact
    {
        return $this->contactRepository->markAsRead($contact);
    }

    /**
     * Delete a contact
     */
    public function deleteContact(Contact $contact): bool
    {
        return $this->contactRepository->delete($contact);
    }

    /**
     * Get contact statistics
     */
    public function getStatistics(): array
    {
        $total = $this->contactRepository->getAllForAdmin()->count();
        $unread = $this->contactRepository->getUnread()->count();
        $read = $this->contactRepository->getRead()->count();

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $read,
            'unread_percentage' => $total > 0 ? round(($unread / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Notify admin about new contact (optional implementation)
     */
    private function notifyAdmin(Contact $contact): void
    {
        // This can be implemented later with email notifications
        // For now, we'll just log it
        \Log::info('New contact submission', [
            'contact_id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email,
        ]);
    }
}
