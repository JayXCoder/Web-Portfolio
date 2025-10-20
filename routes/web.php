<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\SitemapController;

// Main portfolio routes
Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/about', [PortfolioController::class, 'about'])->name('about');
Route::get('/skills', [PortfolioController::class, 'skills'])->name('skills');
Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects');
Route::get('/portfolio', [PortfolioController::class, 'portfolio'])->name('portfolio');
Route::get('/experience', [PortfolioController::class, 'experience'])->name('experience');
Route::get('/contact', [PortfolioController::class, 'contact'])->name('contact');
Route::post('/contact', [PortfolioController::class, 'submitContact'])->name('contact.submit');

// Public API routes for visitor stats
Route::get('/api/visitor-stats', [VisitorController::class, 'getPublicStats'])->name('api.visitor-stats');

// SEO routes
Route::get('/sitemap.xml', function() {
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $sitemap .= '<url><loc>https://jayxcoder.duckdns.org/</loc><lastmod>2025-10-20</lastmod><changefreq>weekly</changefreq><priority>1.0</priority></url>' . "\n";
    $sitemap .= '<url><loc>https://jayxcoder.duckdns.org/about</loc><lastmod>2025-10-20</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>' . "\n";
    $sitemap .= '<url><loc>https://jayxcoder.duckdns.org/skills</loc><lastmod>2025-10-20</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>' . "\n";
    $sitemap .= '<url><loc>https://jayxcoder.duckdns.org/projects</loc><lastmod>2025-10-20</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>' . "\n";
    $sitemap .= '<url><loc>https://jayxcoder.duckdns.org/portfolio</loc><lastmod>2025-10-20</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>' . "\n";
    $sitemap .= '<url><loc>https://jayxcoder.duckdns.org/experience</loc><lastmod>2025-10-20</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>' . "\n";
    $sitemap .= '<url><loc>https://jayxcoder.duckdns.org/contact</loc><lastmod>2025-10-20</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>' . "\n";
    $sitemap .= '</urlset>' . "\n";
    
    return response($sitemap, 200)
        ->header('Content-Type', 'application/xml')
        ->header('Cache-Control', 'public, max-age=3600');
})->name('sitemap');

Route::get('/robots.txt', function() {
    $robots = "User-agent: *\n";
    $robots .= "Allow: /\n\n";
    $robots .= "Sitemap: https://jayxcoder.duckdns.org/sitemap.xml\n";
    
    return response($robots, 200)
        ->header('Content-Type', 'text/plain');
})->name('robots');

// Serve portfolio images through Laravel to bypass permission issues
Route::get('/storage/portfolios/{filename}', function($filename) {
    $path = storage_path('app/public/portfolios/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->name('portfolio.image');

// Serve company logos through Laravel to bypass permission issues
Route::get('/storage/company-logos/{filename}', function($filename) {
    $path = storage_path('app/public/company-logos/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->name('company.logo');

// Individual portfolio item routes
Route::get('/portfolio/{slug}', [PortfolioController::class, 'portfolioItem'])->name('portfolio.item');

// Admin authentication routes
Route::prefix('admin')->name('admin.')->middleware('force.https')->group(function () {
    // Login routes (accessible without authentication)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout.post');
    Route::get('/access-denied', function () {
        return view('admin.access-denied');
    })->name('access-denied');
    
    // Protected admin routes
    Route::middleware('auth')->group(function () {
        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Profile management (Admin only)
        Route::get('/profile', [AdminAuthController::class, 'showProfile'])->name('profile')->middleware('admin');
        Route::put('/profile', [AdminAuthController::class, 'updateProfile'])->middleware('admin');
        
        // Portfolio management
        Route::get('/portfolios', [AdminController::class, 'portfolios'])->name('portfolios');
        Route::get('/portfolios/create', [AdminController::class, 'createPortfolio'])->name('portfolios.create');
        Route::post('/portfolios', [AdminController::class, 'storePortfolio'])->name('portfolios.store');
        Route::get('/portfolios/{portfolio}/edit', [AdminController::class, 'editPortfolio'])->name('portfolios.edit');
        Route::put('/portfolios/{portfolio}', [AdminController::class, 'updatePortfolio'])->name('portfolios.update');
        Route::delete('/portfolios/{portfolio}', [AdminController::class, 'deletePortfolio'])->name('portfolios.delete');
        
        // Work Experience management
        Route::get('/work-experiences', [AdminController::class, 'workExperiences'])->name('work-experiences');
        Route::get('/work-experiences/create', [AdminController::class, 'createWorkExperience'])->name('work-experiences.create');
        Route::post('/work-experiences', [AdminController::class, 'storeWorkExperience'])->name('work-experiences.store');
        Route::get('/work-experiences/{workExperience}/edit', [AdminController::class, 'editWorkExperience'])->name('work-experiences.edit');
        Route::put('/work-experiences/{workExperience}', [AdminController::class, 'updateWorkExperience'])->name('work-experiences.update');
        Route::delete('/work-experiences/{workExperience}', [AdminController::class, 'deleteWorkExperience'])->name('work-experiences.delete');
        
        // Contact management
        Route::get('/contacts', [AdminController::class, 'contacts'])->name('contacts');
        Route::get('/contacts/{contact}', [AdminController::class, 'showContact'])->name('contacts.show');
        Route::patch('/contacts/{contact}/read', [AdminController::class, 'markContactAsRead'])->name('contacts.mark-read');
        Route::delete('/contacts/{contact}', [AdminController::class, 'deleteContact'])->name('contacts.delete');
        
        // Visitor analytics
        Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors');
        Route::get('/visitors/stats', [VisitorController::class, 'getStats'])->name('visitors.stats');
        Route::get('/visitors/daily-stats', [VisitorController::class, 'getDailyStats'])->name('visitors.daily-stats');
        Route::get('/visitors/most-visited', [VisitorController::class, 'getMostVisitedPages'])->name('visitors.most-visited');
        Route::get('/visitors/device-stats', [VisitorController::class, 'getDeviceStats'])->name('visitors.device-stats');
        Route::get('/visitors/browser-stats', [VisitorController::class, 'getBrowserStats'])->name('visitors.browser-stats');
        Route::get('/visitors/country-stats', [VisitorController::class, 'getCountryStats'])->name('visitors.country-stats');
        Route::get('/visitors/recent', [VisitorController::class, 'getRecentVisitors'])->name('visitors.recent');
        Route::get('/visitors/export', [VisitorController::class, 'export'])->name('visitors.export');
        
        // User management (admin only)
        Route::middleware('admin')->group(function () {
            Route::get('/users', [AdminUserController::class, 'index'])->name('users');
            Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
            Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.delete');
            Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        });
    });
});
