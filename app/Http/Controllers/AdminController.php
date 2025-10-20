<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Visitor;
use App\Models\Portfolio;
use App\Models\WorkExperience;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\ContactService;
use App\Services\PortfolioService;
use App\Services\WorkExperienceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function __construct(
        private PortfolioService $portfolioService,
        private ContactService $contactService,
        private WorkExperienceService $workExperienceService
    ) {
        // Middleware is applied in routes/web.php
    }

    /**
     * Show admin dashboard
     */
    public function dashboard(): View
    {
        $stats = [
            'total_portfolios' => $this->portfolioService->getAllForAdmin()->count(),
            'published_portfolios' => $this->portfolioService->getAllPublished()->count(),
            'featured_portfolios' => $this->portfolioService->getFeatured()->count(),
            'total_contacts' => $this->contactService->getAllForAdmin()->count(),
            'unread_contacts' => $this->contactService->getUnread()->count(),
            'contact_stats' => $this->contactService->getStatistics(),
        ];

        // Add visitor stats if table exists
        try {
            if (Schema::hasTable('visitors')) {
                $stats['total_visitors'] = Visitor::getUniqueVisitorCount();
                $stats['total_page_views'] = Visitor::getTotalPageViews();
                $stats['today_visitors'] = Visitor::whereDate('created_at', today())->count();
            } else {
                $stats['total_visitors'] = 0;
                $stats['total_page_views'] = 0;
                $stats['today_visitors'] = 0;
            }
        } catch (\Exception $e) {
            $stats['total_visitors'] = 0;
            $stats['total_page_views'] = 0;
            $stats['today_visitors'] = 0;
        }

        $recentContacts = $this->contactService->getAllForAdmin()->take(5);
        $recentPortfolios = $this->portfolioService->getAllForAdmin()->take(5);

        return view('admin.dashboard', compact('stats', 'recentContacts', 'recentPortfolios'));
    }

    /**
     * Show all portfolios for admin management
     */
    public function portfolios(): View
    {
        $portfolios = $this->portfolioService->getAllForAdmin();
        return view('admin.portfolios.index', compact('portfolios'));
    }

    /**
     * Show create portfolio form
     */
    public function createPortfolio(): View
    {
        return view('admin.portfolios.create');
    }

    /**
     * Store new portfolio
     */
    public function storePortfolio(Request $request): RedirectResponse
    {
        // Convert comma-separated strings to arrays
        if ($request->has('technologies') && is_string($request->technologies)) {
            $request->merge(['technologies' => array_map('trim', explode(',', $request->technologies))]);
        }
        if ($request->has('features') && is_string($request->features)) {
            $request->merge(['features' => array_map('trim', explode(',', $request->features))]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'technologies' => 'required|array|min:1',
            'technologies.*' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|max:200',
            'duration_months' => 'nullable|integer|min:1',
            'client' => 'nullable|string|max:255',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_urls' => 'nullable|string',
        ]);

        $portfolio = $this->portfolioService->createPortfolio($request->all());

        return redirect()->to(url(route('admin.portfolios'), [], true))
            ->with('success', 'Portfolio created successfully!');
    }

    /**
     * Show edit portfolio form
     */
    public function editPortfolio(Portfolio $portfolio): View
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    /**
     * Update portfolio
     */
    public function updatePortfolio(Request $request, Portfolio $portfolio): RedirectResponse
    {
        // Convert comma-separated strings to arrays
        if ($request->has('technologies') && is_string($request->technologies)) {
            $request->merge(['technologies' => array_map('trim', explode(',', $request->technologies))]);
        }
        if ($request->has('features') && is_string($request->features)) {
            $request->merge(['features' => array_map('trim', explode(',', $request->features))]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'technologies' => 'required|array|min:1',
            'technologies.*' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|max:200',
            'duration_months' => 'nullable|integer|min:1',
            'client' => 'nullable|string|max:255',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_urls' => 'nullable|string',
        ]);

        $this->portfolioService->updatePortfolio($portfolio, $request->all());

        return redirect()->to(url(route('admin.portfolios'), [], true))
            ->with('success', 'Portfolio updated successfully!');
    }

    /**
     * Delete portfolio
     */
    public function deletePortfolio(Portfolio $portfolio): RedirectResponse
    {
        $this->portfolioService->deletePortfolio($portfolio);

        return redirect()->to(url(route('admin.portfolios'), [], true))
            ->with('success', 'Portfolio deleted successfully!');
    }

    /**
     * Show all work experiences for admin management
     */
    public function workExperiences(): View
    {
        $workExperiences = $this->workExperienceService->getAllForAdmin();
        return view('admin.work-experiences.index', compact('workExperiences'));
    }

    /**
     * Show create work experience form
     */
    public function createWorkExperience(): View
    {
        return view('admin.work-experiences.create');
    }

    /**
     * Store new work experience
     */
    public function storeWorkExperience(Request $request): RedirectResponse
    {
        // Convert comma-separated strings to arrays
        if ($request->has('technologies') && is_string($request->technologies)) {
            $request->merge(['technologies' => array_map('trim', explode(',', $request->technologies))]);
        }
        if ($request->has('achievements') && is_string($request->achievements)) {
            $request->merge(['achievements' => array_map('trim', explode(',', $request->achievements))]);
        }
        if ($request->has('skills_gained') && is_string($request->skills_gained)) {
            $request->merge(['skills_gained' => array_map('trim', explode(',', $request->skills_gained))]);
        }

        $request->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'employment_type' => 'required|in:Full-Time,Part-Time,Internship,Contract,Freelance',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:100',
            'achievements' => 'nullable|array',
            'achievements.*' => 'string|max:200',
            'skills_gained' => 'nullable|array',
            'skills_gained.*' => 'string|max:100',
            'team_size' => 'nullable|integer|min:1',
            'reporting_to' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $workExperience = $this->workExperienceService->createWorkExperience($request->all());

        return redirect()->to(url(route('admin.work-experiences'), [], true))
            ->with('success', 'Work experience created successfully!');
    }

    /**
     * Show edit work experience form
     */
    public function editWorkExperience(WorkExperience $workExperience): View
    {
        return view('admin.work-experiences.edit', compact('workExperience'));
    }

    /**
     * Update work experience
     */
    public function updateWorkExperience(Request $request, WorkExperience $workExperience): RedirectResponse
    {
        // Convert comma-separated strings to arrays
        if ($request->has('technologies') && is_string($request->technologies)) {
            $request->merge(['technologies' => array_map('trim', explode(',', $request->technologies))]);
        }
        if ($request->has('achievements') && is_string($request->achievements)) {
            $request->merge(['achievements' => array_map('trim', explode(',', $request->achievements))]);
        }
        if ($request->has('skills_gained') && is_string($request->skills_gained)) {
            $request->merge(['skills_gained' => array_map('trim', explode(',', $request->skills_gained))]);
        }

        $request->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'employment_type' => 'required|in:Full-Time,Part-Time,Internship,Contract,Freelance',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:100',
            'achievements' => 'nullable|array',
            'achievements.*' => 'string|max:200',
            'skills_gained' => 'nullable|array',
            'skills_gained.*' => 'string|max:100',
            'team_size' => 'nullable|integer|min:1',
            'reporting_to' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $this->workExperienceService->updateWorkExperience($workExperience, $request->all());

        return redirect()->to(url(route('admin.work-experiences'), [], true))
            ->with('success', 'Work experience updated successfully!');
    }

    /**
     * Delete work experience
     */
    public function deleteWorkExperience(WorkExperience $workExperience): RedirectResponse
    {
        $this->workExperienceService->deleteWorkExperience($workExperience);

        return redirect()->to(url(route('admin.work-experiences'), [], true))
            ->with('success', 'Work experience deleted successfully!');
    }

    /**
     * Show all contacts for admin management
     */
    public function contacts(): View
    {
        $contacts = $this->contactService->getAllForAdmin();
        $stats = $this->contactService->getStatistics();
        
        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    /**
     * Show individual contact
     */
    public function showContact(Contact $contact): View
    {
        // Mark as read when viewed
        if (!$contact->is_read) {
            $this->contactService->markAsRead($contact);
        }
        
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark contact as read
     */
    public function markContactAsRead(Contact $contact): RedirectResponse
    {
        $this->contactService->markAsRead($contact);
        
        return redirect()->back()
            ->with('success', 'Contact marked as read.');
    }

    /**
     * Delete contact
     */
    public function deleteContact(Contact $contact): RedirectResponse
    {
        $this->contactService->deleteContact($contact);
        
        return redirect()->to(url(route('admin.contacts'), [], true))
            ->with('success', 'Contact deleted successfully.');
    }
}