<?php

namespace App\Repositories\Interfaces;

use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Collection;

interface PortfolioRepositoryInterface
{
    /**
     * Get all published portfolios ordered by sort order
     */
    public function getAllPublished(): Collection;

    /**
     * Get featured portfolios
     */
    public function getFeatured(): Collection;

    /**
     * Get portfolio by slug
     */
    public function getBySlug(string $slug): ?Portfolio;

    /**
     * Get portfolios by category
     */
    public function getByCategory(string $category): Collection;

    /**
     * Create a new portfolio
     */
    public function create(array $data): Portfolio;

    /**
     * Update a portfolio
     */
    public function update(Portfolio $portfolio, array $data): Portfolio;

    /**
     * Delete a portfolio
     */
    public function delete(Portfolio $portfolio): bool;

    /**
     * Get all portfolios (including unpublished) for admin
     */
    public function getAllForAdmin(): Collection;
}
