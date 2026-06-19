<?php

namespace App\Services;

use App\Models\Achievement;
use App\Repositories\Interfaces\AchievementRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        if (isset($data['badge_image']) && $data['badge_image'] instanceof UploadedFile) {
            $data['badge_image'] = $this->uploadImage($data['badge_image'], 'achievement-badges');
        }

        if (isset($data['award_photo']) && $data['award_photo'] instanceof UploadedFile) {
            $data['award_photo'] = $this->uploadImage($data['award_photo'], 'achievement-photos');
        }

        unset($data['remove_badge_image'], $data['remove_award_photo']);

        if (! isset($data['sort_order'])) {
            $data['sort_order'] = $this->getNextSortOrder();
        }

        return $this->achievementRepository->create($data);
    }

    public function updateAchievement(Achievement $achievement, array $data): Achievement
    {
        if (! empty($data['remove_badge_image'])) {
            $this->deleteStoredImage($achievement->badge_image);
            $data['badge_image'] = null;
        } elseif (isset($data['badge_image']) && $data['badge_image'] instanceof UploadedFile) {
            $this->deleteStoredImage($achievement->badge_image);
            $data['badge_image'] = $this->uploadImage($data['badge_image'], 'achievement-badges');
        } else {
            unset($data['badge_image']);
        }

        if (! empty($data['remove_award_photo'])) {
            $this->deleteStoredImage($achievement->award_photo);
            $data['award_photo'] = null;
        } elseif (isset($data['award_photo']) && $data['award_photo'] instanceof UploadedFile) {
            $this->deleteStoredImage($achievement->award_photo);
            $data['award_photo'] = $this->uploadImage($data['award_photo'], 'achievement-photos');
        } else {
            unset($data['award_photo']);
        }

        unset($data['remove_badge_image'], $data['remove_award_photo']);

        return $this->achievementRepository->update($achievement, $data);
    }

    public function deleteAchievement(Achievement $achievement): bool
    {
        $this->deleteStoredImage($achievement->badge_image);
        $this->deleteStoredImage($achievement->award_photo);

        return $this->achievementRepository->delete($achievement);
    }

    private function uploadImage(UploadedFile $file, string $directory): string
    {
        $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

        return $file->storeAs($directory, $filename, 'public');
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && ! filter_var($path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function getNextSortOrder(): int
    {
        return (Achievement::max('sort_order') ?? 0) + 1;
    }
}
