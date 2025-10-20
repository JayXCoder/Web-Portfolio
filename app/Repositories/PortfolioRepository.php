<?php

namespace App\Repositories;

use App\Models\Portfolio;
use App\Repositories\Interfaces\PortfolioRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PortfolioRepository implements PortfolioRepositoryInterface
{
    public function __construct(
        private Portfolio $model
    ) {}

    /**
     * Get all published portfolios ordered by sort order
     */
    public function getAllPublished(): Collection
    {
        return $this->model
            ->published()
            ->ordered()
            ->get();
    }

    /**
     * Get featured portfolios
     */
    public function getFeatured(): Collection
    {
        return $this->model
            ->published()
            ->featured()
            ->ordered()
            ->get();
    }

    /**
     * Get portfolio by slug
     */
    public function getBySlug(string $slug): ?Portfolio
    {
        return $this->model
            ->published()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Get portfolios by category
     */
    public function getByCategory(string $category): Collection
    {
        return $this->model
            ->published()
            ->byCategory($category)
            ->ordered()
            ->get();
    }

    /**
     * Create a new portfolio
     */
    public function create(array $data): Portfolio
    {
        return $this->model->create($data);
    }

    /**
     * Update a portfolio
     */
    public function update(Portfolio $portfolio, array $data): Portfolio
    {
        $portfolio->update($data);
        return $portfolio->fresh();
    }

    /**
     * Delete a portfolio
     */
    public function delete(Portfolio $portfolio): bool
    {
        return $portfolio->delete();
    }

    /**
     * Get all portfolios (including unpublished) for admin
     */
    public function getAllForAdmin(): Collection
    {
        return $this->model
            ->ordered()
            ->get();
    }
}
