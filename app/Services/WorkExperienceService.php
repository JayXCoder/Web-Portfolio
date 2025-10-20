<?php

namespace App\Services;

use App\Models\WorkExperience;
use App\Repositories\Interfaces\WorkExperienceRepositoryInterface;
use App\Services\WorkExperience\Contracts\ImageHandlerInterface;
use App\Services\WorkExperience\Contracts\DataProcessorInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class WorkExperienceService
{
    public function __construct(
        private WorkExperienceRepositoryInterface $workExperienceRepository,
        private ImageHandlerInterface $imageHandler,
        private DataProcessorInterface $dataProcessor
    ) {}

    /**
     * Get all published work experiences
     */
    public function getAllPublished(): Collection
    {
        return $this->workExperienceRepository->getAllPublished();
    }

    /**
     * Get work experiences by employment type
     */
    public function getByEmploymentType(string $type): Collection
    {
        return $this->workExperienceRepository->getByEmploymentType($type);
    }

    /**
     * Get current work experiences
     */
    public function getCurrent(): Collection
    {
        return $this->workExperienceRepository->getCurrent();
    }

    /**
     * Create a new work experience
     */
    public function createWorkExperience(array $data): WorkExperience
    {
        // Handle company logo upload
        if (isset($data['company_logo']) && $data['company_logo'] instanceof UploadedFile) {
            $data['company_logo'] = $this->imageHandler->uploadLogo($data['company_logo']);
        }

        // Process data
        $data = $this->dataProcessor->processWorkExperienceData($data);

        // Set sort order if not provided
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = $this->getNextSortOrder();
        }

        return $this->workExperienceRepository->create($data);
    }

    /**
     * Update a work experience
     */
    public function updateWorkExperience(WorkExperience $workExperience, array $data): WorkExperience
    {
        // Handle company logo upload
        if (isset($data['company_logo']) && $data['company_logo'] instanceof UploadedFile) {
            // Delete old logo if exists
            $this->imageHandler->deleteLogo($workExperience->company_logo);
            $data['company_logo'] = $this->imageHandler->uploadLogo($data['company_logo']);
        }

        // Process data
        $data = $this->dataProcessor->processWorkExperienceData($data);

        return $this->workExperienceRepository->update($workExperience, $data);
    }

    /**
     * Delete a work experience
     */
    public function deleteWorkExperience(WorkExperience $workExperience): bool
    {
        // Delete company logo if exists
        $this->imageHandler->deleteLogo($workExperience->company_logo);

        return $this->workExperienceRepository->delete($workExperience);
    }

    /**
     * Get all work experiences for admin
     */
    public function getAllForAdmin(): Collection
    {
        return $this->workExperienceRepository->getAllForAdmin();
    }


    /**
     * Get next sort order
     */
    private function getNextSortOrder(): int
    {
        $maxOrder = WorkExperience::max('sort_order') ?? 0;
        return $maxOrder + 1;
    }
}
