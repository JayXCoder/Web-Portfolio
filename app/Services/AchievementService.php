<?php

namespace App\Services;

use App\Models\Achievement;
use App\Repositories\Interfaces\AchievementRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AchievementService
{
    public function __construct(
        private AchievementRepositoryInterface $achievementRepository
    ) {}

    public function getAllPublished(): Collection
    {
        return $this->achievementRepository->getAllPublished();
    }

    public function getAllForAdmin(): Collection
    {
        return $this->achievementRepository->getAllForAdmin();
    }

    public function createAchievement(array $data): Achievement
    {
        if (! isset($data['sort_order'])) {
            $data['sort_order'] = $this->getNextSortOrder();
        }

        return $this->achievementRepository->create($data);
    }

    public function updateAchievement(Achievement $achievement, array $data): Achievement
    {
        return $this->achievementRepository->update($achievement, $data);
    }

    public function deleteAchievement(Achievement $achievement): bool
    {
        return $this->achievementRepository->delete($achievement);
    }

    private function getNextSortOrder(): int
    {
        return (Achievement::max('sort_order') ?? 0) + 1;
    }
}
