<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\AchievementService;
use App\Services\PortfolioService;
use App\Services\ContactService;
use App\Services\WorkExperienceService;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function __construct(
        private PortfolioService $portfolioService,
        private AchievementService $achievementService,
        private ContactService $contactService,
        private WorkExperienceService $workExperienceService
    ) {}

    /**
     * Display the home page.
     */
    public function home()
    {
        $featuredProjects = $this->portfolioService->getFeatured();
        if ($featuredProjects->isEmpty()) {
            $featuredProjects = $this->portfolioService->getAllPublished()->take(8);
        } else {
            $featuredProjects = $featuredProjects->take(8);
        }

        $achievements = $this->achievementService->getAllPublished()->take(4);
        $experiences = $this->workExperienceService->getAllPublished()->take(3);
        $allProjects = $this->portfolioService->getAllPublished();

        $marqueeSkills = collect(config('skills.tree.apex.skills', []))
            ->merge(collect(config('skills.tree.branches', []))->flatMap(fn ($b) => $b['skills'] ?? []))
            ->pluck('label')
            ->unique()
            ->take(24)
            ->values()
            ->all();

        $stats = [
            'projects' => $allProjects->count(),
            'achievements' => $this->achievementService->getAllPublished()->count(),
            'roles' => $this->workExperienceService->getAllPublished()->count(),
            'skills' => count($marqueeSkills),
        ];

        return view('pages.home', compact(
            'featuredProjects',
            'achievements',
            'experiences',
            'marqueeSkills',
            'stats',
        ));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display the skills page.
     */
    public function skills()
    {
        return view('pages.skills');
    }

    /**
     * Display the achievements page.
     */
    public function achievements()
    {
        $achievements = $this->achievementService->getAllPublished();
        $credlyProfile = config('achievements.credly_profile');

        return view('pages.achievements', compact('achievements', 'credlyProfile'));
    }

    /**
     * Display the projects page.
     */
    public function projects()
    {
        $portfolioItems = $this->portfolioService->getFeatured();

        if ($portfolioItems->isEmpty()) {
            $portfolioItems = $this->portfolioService->getAllPublished()->take(6);
        }

        return view('pages.projects', compact('portfolioItems'));
    }

    /**
     * Display the portfolio page.
     */
    public function portfolio()
    {
        $portfolioItems = $this->portfolioService->getAllPublished();
        
        return view('pages.portfolio', compact('portfolioItems'));
    }

    /**
     * Display the work experience page.
     */
    public function experience()
    {
        $workExperiences = $this->workExperienceService->getAllPublished();
        
        return view('pages.experience', compact('workExperiences'));
    }

    /**
     * Display individual portfolio item.
     */
    public function portfolioItem($slug)
    {
        $portfolio = $this->portfolioService->getBySlug($slug);
        
        if (!$portfolio) {
            abort(404, 'Portfolio item not found');
        }
        
        return view('pages.portfolio-item', compact('portfolio'));
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'organization' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Create contact using service
        $this->contactService->createContact($request->only([
            'name', 'email', 'organization', 'university', 'phone', 'message'
        ]));

        return redirect()->route('contact')
            ->with('success', 'Thank you for your message! I will get back to you soon.');
    }
}