<?php

namespace App\Repositories;

use App\Models\Achievement;
use App\Repositories\Interfaces\AchievementRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AchievementRepository implements AchievementRepositoryInterface
{
    public function __construct(
        private Achievement $model
    ) {}

    public function getAllPublished(): Collection
    {
        return $this->model
            ->published()
            ->ordered()
            ->get();
    }

    public function getAllForAdmin(): Collection
    {
        return $this->model
            ->ordered()
            ->get();
    }

    public function create(array $data): Achievement
    {
        return $this->model->create($data);
    }

    public function update(Achievement $achievement, array $data): Achievement
    {
        $achievement->update($data);

        return $achievement->fresh();
    }

    public function delete(Achievement $achievement): bool
    {
        return $achievement->delete();
    }
}
