<?php

namespace App\Repositories;

use App\Models\WorkExperience;
use App\Repositories\Interfaces\WorkExperienceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WorkExperienceRepository implements WorkExperienceRepositoryInterface
{
    /**
     * Get all published work experiences ordered by start date (newest first)
     */
    public function getAllPublished(): Collection
    {
        return WorkExperience::published()
            ->ordered()
            ->get();
    }

    /**
     * Get all work experiences for admin management
     */
    public function getAllForAdmin(): Collection
    {
        return WorkExperience::ordered()->get();
    }

    /**
     * Get work experiences by employment type
     */
    public function getByEmploymentType(string $type): Collection
    {
        return WorkExperience::published()
            ->byEmploymentType($type)
            ->ordered()
            ->get();
    }

    /**
     * Get current work experiences
     */
    public function getCurrent(): Collection
    {
        return WorkExperience::published()
            ->current()
            ->ordered()
            ->get();
    }

    /**
     * Create a new work experience
     */
    public function create(array $data): WorkExperience
    {
        return WorkExperience::create($data);
    }

    /**
     * Update a work experience
     */
    public function update(WorkExperience $workExperience, array $data): WorkExperience
    {
        $workExperience->update($data);
        return $workExperience->fresh();
    }

    /**
     * Delete a work experience
     */
    public function delete(WorkExperience $workExperience): bool
    {
        return $workExperience->delete();
    }

    /**
     * Find work experience by ID
     */
    public function findById(int $id): ?WorkExperience
    {
        return WorkExperience::find($id);
    }
}
