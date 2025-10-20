<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\WorkExperience;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     */
    public function index(): Response
    {
        $portfolios = Portfolio::published()->get();
        $workExperiences = WorkExperience::published()->get();
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Static pages
        $staticPages = [
            [
                'url' => url('/'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ],
            [
                'url' => url('/about'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'url' => url('/skills'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ],
            [
                'url' => url('/projects'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'url' => url('/portfolio'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'url' => url('/experience'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'url' => url('/contact'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ]
        ];
        
        // Add static pages
        foreach ($staticPages as $page) {
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . htmlspecialchars($page['url']) . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . $page['lastmod'] . '</lastmod>' . "\n";
            $sitemap .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
            $sitemap .= '    <priority>' . $page['priority'] . '</priority>' . "\n";
            $sitemap .= '  </url>' . "\n";
        }
        
        // Add portfolio items
        foreach ($portfolios as $portfolio) {
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . htmlspecialchars(route('portfolio.item', $portfolio->slug)) . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . $portfolio->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
            $sitemap .= '    <changefreq>monthly</changefreq>' . "\n";
            $sitemap .= '    <priority>0.7</priority>' . "\n";
            $sitemap .= '  </url>' . "\n";
        }
        
        $sitemap .= '</urlset>' . "\n";
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
