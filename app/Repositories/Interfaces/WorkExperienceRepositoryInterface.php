<?php

namespace App\Repositories\Interfaces;

use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Collection;

interface WorkExperienceRepositoryInterface
{
    /**
     * Get all published work experiences ordered by start date (newest first)
     */
    public function getAllPublished(): Collection;

    /**
     * Get all work experiences for admin management
     */
    public function getAllForAdmin(): Collection;

    /**
     * Get work experiences by employment type
     */
    public function getByEmploymentType(string $type): Collection;

    /**
     * Get current work experiences
     */
    public function getCurrent(): Collection;

    /**
     * Create a new work experience
     */
    public function create(array $data): WorkExperience;

    /**
     * Update a work experience
     */
    public function update(WorkExperience $workExperience, array $data): WorkExperience;

    /**
     * Delete a work experience
     */
    public function delete(WorkExperience $workExperience): bool;

    /**
     * Find work experience by ID
     */
    public function findById(int $id): ?WorkExperience;
}
