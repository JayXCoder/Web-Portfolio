<?php

namespace App\Repositories\Interfaces;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Collection;

interface AchievementRepositoryInterface
{
    public function getAllPublished(): Collection;

    public function getAllForAdmin(): Collection;

    public function create(array $data): Achievement;

    public function update(Achievement $achievement, array $data): Achievement;

    public function delete(Achievement $achievement): bool;
}
