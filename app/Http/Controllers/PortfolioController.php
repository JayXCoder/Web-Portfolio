<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\PortfolioService;
use App\Services\ContactService;
use App\Services\WorkExperienceService;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function __construct(
        private PortfolioService $portfolioService,
        private ContactService $contactService,
        private WorkExperienceService $workExperienceService
    ) {}

    /**
     * Display the home page.
     */
    public function home()
    {
        return view('pages.home');
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
     * Display the projects page.
     */
    public function projects()
    {
        return view('pages.projects');
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