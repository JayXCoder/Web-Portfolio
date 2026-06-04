<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Repositories\Interfaces\PortfolioRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioService
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository
    ) {}

    /**
     * Get all published portfolios
     */
    public function getAllPublished(): Collection
    {
        return $this->portfolioRepository->getAllPublished();
    }

    /**
     * Get featured portfolios
     */
    public function getFeatured(): Collection
    {
        return $this->portfolioRepository->getFeatured();
    }

    /**
     * Get portfolio by slug
     */
    public function getBySlug(string $slug): ?Portfolio
    {
        return $this->portfolioRepository->getBySlug($slug);
    }

    /**
     * Get portfolios by category
     */
    public function getByCategory(string $category): Collection
    {
        return $this->portfolioRepository->getByCategory($category);
    }

    /**
     * Create a new portfolio
     */
    public function createPortfolio(array $data): Portfolio
    {
        // Handle image uploads and URLs
        $images = [];
        
        // Handle file uploads
        if (isset($data['images']) && !empty($data['images'])) {
            $images = array_merge($images, $this->handleImageUploads($data['images']));
        }
        
        if (! empty($data['image_urls']) && is_string($data['image_urls'])) {
            $images = $this->mergeExternalImageUrls($images, $data['image_urls']);
        }

        $data['images'] = array_values(array_unique($images));

        // Clean up data
        unset($data['image_urls']);

        // Ensure slug is unique
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        return $this->portfolioRepository->create($data);
    }

    /**
     * Update a portfolio
     */
    public function updatePortfolio(Portfolio $portfolio, array $data): Portfolio
    {
        // Start with existing images
        $finalImages = $portfolio->images ?? [];
        
        // Handle image removal
        if (isset($data['remove_images']) && is_array($data['remove_images'])) {
            foreach ($data['remove_images'] as $index) {
                if (isset($finalImages[$index])) {
                    // Delete the file from storage
                    Storage::disk('public')->delete($finalImages[$index]);
                    // Remove from array
                    unset($finalImages[$index]);
                }
            }
            // Re-index the array
            $finalImages = array_values($finalImages);
        }

        // Handle new image uploads (form field: images[] or legacy new_images[])
        $uploads = $data['images'] ?? $data['new_images'] ?? null;
        if (! empty($uploads) && is_array($uploads)) {
            $newImages = $this->handleImageUploads($uploads);
            $finalImages = array_merge($finalImages, $newImages);
        }

        // External image URLs only (stored paths are kept from $finalImages)
        if (! empty($data['image_urls']) && is_string($data['image_urls'])) {
            $finalImages = $this->mergeExternalImageUrls($finalImages, $data['image_urls']);
        }

        $data['images'] = array_values(array_unique($finalImages));

        unset($data['new_images'], $data['remove_images'], $data['image_urls']);

        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $portfolio->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $portfolio->id);
        }

        return $this->portfolioRepository->update($portfolio, $data);
    }

    /**
     * Delete a portfolio
     */
    public function deletePortfolio(Portfolio $portfolio): bool
    {
        // Delete associated images
        if ($portfolio->images) {
            foreach ($portfolio->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        return $this->portfolioRepository->delete($portfolio);
    }

    /**
     * Get all portfolios for admin
     */
    public function getAllForAdmin(): Collection
    {
        return $this->portfolioRepository->getAllForAdmin();
    }

    /**
     * @param  list<string>  $images
     * @return list<string>
     */
    private function mergeExternalImageUrls(array $images, string $imageUrls): array
    {
        foreach (array_map('trim', explode(',', $imageUrls)) as $entry) {
            if ($entry === '') {
                continue;
            }

            if (filter_var($entry, FILTER_VALIDATE_URL)) {
                $images[] = $entry;
            }
        }

        return $images;
    }

    /**
     * Handle image uploads
     */
    private function handleImageUploads(array $images, array $existingImages = []): array
    {
        $uploadedImages = [];

        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('portfolios', $filename, 'public');
                $uploadedImages[] = $path;
            } else {
                // Keep existing image path
                $uploadedImages[] = $image;
            }
        }

        return $uploadedImages;
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Portfolio::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
