<?php

namespace App\Providers;

use App\Repositories\AchievementRepository;
use App\Repositories\BlogCommentRepository;
use App\Repositories\BlogPostRepository;
use App\Repositories\ContactRepository;
use App\Repositories\PortfolioRepository;
use App\Repositories\WorkExperienceRepository;
use App\Repositories\Interfaces\AchievementRepositoryInterface;
use App\Repositories\Interfaces\BlogCommentRepositoryInterface;
use App\Repositories\Interfaces\BlogPostRepositoryInterface;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Repositories\Interfaces\PortfolioRepositoryInterface;
use App\Repositories\Interfaces\WorkExperienceRepositoryInterface;
use App\Services\AchievementService;
use App\Services\BlogCommentService;
use App\Services\BlogPostService;
use App\Services\ContactService;
use App\Services\PortfolioService;
use App\Services\WorkExperienceService;
use App\Services\WorkExperience\ImageHandler;
use App\Services\WorkExperience\DataProcessor;
use App\Services\WorkExperience\Contracts\ImageHandlerInterface;
use App\Services\WorkExperience\Contracts\DataProcessorInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(PortfolioRepositoryInterface::class, PortfolioRepository::class);
        $this->app->bind(AchievementRepositoryInterface::class, AchievementRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(WorkExperienceRepositoryInterface::class, WorkExperienceRepository::class);
        $this->app->bind(BlogPostRepositoryInterface::class, BlogPostRepository::class);
        $this->app->bind(BlogCommentRepositoryInterface::class, BlogCommentRepository::class);

        // Service bindings
        $this->app->bind(PortfolioService::class, function ($app) {
            return new PortfolioService($app->make(PortfolioRepositoryInterface::class));
        });

        $this->app->bind(AchievementService::class, function ($app) {
            return new AchievementService($app->make(AchievementRepositoryInterface::class));
        });

        $this->app->bind(ContactService::class, function ($app) {
            return new ContactService($app->make(ContactRepositoryInterface::class));
        });

        $this->app->bind(BlogPostService::class, function ($app) {
            return new BlogPostService($app->make(BlogPostRepositoryInterface::class));
        });

        $this->app->bind(BlogCommentService::class, function ($app) {
            return new BlogCommentService($app->make(BlogCommentRepositoryInterface::class));
        });

        $this->app->bind(WorkExperienceService::class, function ($app) {
            return new WorkExperienceService(
                $app->make(WorkExperienceRepositoryInterface::class),
                $app->make(ImageHandlerInterface::class),
                $app->make(DataProcessorInterface::class)
            );
        });

        // Bind concrete implementations to interfaces
        $this->app->bind(ImageHandlerInterface::class, ImageHandler::class);
        $this->app->bind(DataProcessorInterface::class, DataProcessor::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
