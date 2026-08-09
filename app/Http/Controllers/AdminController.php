<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Contact;
use App\Models\Visitor;
use App\Models\Portfolio;
use App\Models\WorkExperience;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\AchievementService;
use App\Services\ContactService;
use App\Services\PortfolioService;
use App\Services\WorkExperienceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function __construct(
        private PortfolioService $portfolioService,
        private AchievementService $achievementService,
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

        $this->mergePortfolioCheckboxFields($request);

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
            'is_featured' => 'required|boolean',
            'is_published' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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

        $this->mergePortfolioCheckboxFields($request);

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
            'is_featured' => 'required|boolean',
            'is_published' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_urls' => 'nullable|string',
            'image_order' => 'nullable|array',
            'image_order.*' => 'string|max:500',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string|max:500',
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
     * Show all achievements for admin management
     */
    public function achievements(): View
    {
        $achievements = $this->achievementService->getAllForAdmin();

        return view('admin.achievements.index', compact('achievements'));
    }

    /**
     * Show create achievement form
     */
    public function createAchievement(): View
    {
        return view('admin.achievements.create');
    }

    /**
     * Store new achievement
     */
    public function storeAchievement(Request $request): RedirectResponse
    {
        if ($request->has('skills') && is_string($request->skills)) {
            $request->merge(['skills' => array_values(array_filter(array_map('trim', explode(',', $request->skills))))]);
        }

        $this->mergeAchievementCheckboxFields($request);

        $request->validate([
            'type' => 'required|string|in:'.implode(',', array_keys(config('achievements.types', []))),
            'organization' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'placement' => 'nullable|string|max:255',
            'story' => 'required|string',
            'project' => 'nullable|string',
            'issued_date' => 'nullable|date',
            'credly_url' => 'nullable|url|max:500',
            'image_url' => 'nullable|url|max:500',
            'badge_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'award_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'required|boolean',
        ]);

        $this->achievementService->createAchievement($request->all());

        return redirect()->to(url(route('admin.achievements'), [], true))
            ->with('success', 'Achievement created successfully!');
    }

    /**
     * Show edit achievement form
     */
    public function editAchievement(Achievement $achievement): View
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    /**
     * Update achievement
     */
    public function updateAchievement(Request $request, Achievement $achievement): RedirectResponse
    {
        if ($request->has('skills') && is_string($request->skills)) {
            $request->merge(['skills' => array_values(array_filter(array_map('trim', explode(',', $request->skills))))]);
        }

        $this->mergeAchievementCheckboxFields($request);

        $request->validate([
            'type' => 'required|string|in:'.implode(',', array_keys(config('achievements.types', []))),
            'organization' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'placement' => 'nullable|string|max:255',
            'story' => 'required|string',
            'project' => 'nullable|string',
            'issued_date' => 'nullable|date',
            'credly_url' => 'nullable|url|max:500',
            'image_url' => 'nullable|url|max:500',
            'badge_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'award_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'remove_badge_image' => 'nullable|boolean',
            'remove_award_photo' => 'nullable|boolean',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'required|boolean',
        ]);

        $this->achievementService->updateAchievement($achievement, $request->all());

        return redirect()->to(url(route('admin.achievements'), [], true))
            ->with('success', 'Achievement updated successfully!');
    }

    /**
     * Delete achievement
     */
    public function deleteAchievement(Achievement $achievement): RedirectResponse
    {
        $this->achievementService->deleteAchievement($achievement);

        return redirect()->to(url(route('admin.achievements'), [], true))
            ->with('success', 'Achievement deleted successfully!');
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

    /**
     * Delete multiple contacts
     */
    public function bulkDeleteContacts(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1|max:200',
            'ids.*' => 'integer|distinct|exists:contacts,id',
        ]);

        $deleted = $this->contactService->deleteContactsByIds($validated['ids']);

        return redirect()->to(url(route('admin.contacts'), [], true))
            ->with('success', $deleted === 1
                ? '1 contact deleted.'
                : "{$deleted} contacts deleted.");
    }

    /**
     * Unchecked HTML checkboxes are omitted from the request; normalize to false.
     */
    private function mergePortfolioCheckboxFields(Request $request): void
    {
        $request->merge([
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
        ]);
    }

    private function mergeAchievementCheckboxFields(Request $request): void
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
            'remove_badge_image' => $request->boolean('remove_badge_image'),
            'remove_award_photo' => $request->boolean('remove_award_photo'),
        ]);
    }
}