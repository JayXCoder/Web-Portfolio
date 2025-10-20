<?php

namespace App\Http\Controllers;

use Schema;
use Carbon\Carbon;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Display visitor statistics dashboard
     */
    public function index()
    {
        $stats = [
            'total_visitors' => Visitor::getUniqueVisitorCount(),
            'total_page_views' => Visitor::getTotalPageViews(),
            'today_visitors' => Visitor::whereDate('created_at', today())->count(),
            'today_page_views' => Visitor::whereDate('created_at', today())->sum('visit_count'),
            'this_week_visitors' => Visitor::where('created_at', '>=', now()->subWeek())->count(),
            'this_month_visitors' => Visitor::where('created_at', '>=', now()->subMonth())->count(),
        ];

        $most_visited_pages = Visitor::getMostVisitedPages(10);
        $visitors_by_device = Visitor::getVisitorsByDevice();
        $visitors_by_browser = Visitor::getVisitorsByBrowser();
        $visitors_by_country = Visitor::getVisitorsByCountry();
        $daily_stats = Visitor::getDailyStats(30);

        return view('admin.visitors.index', compact(
            'stats',
            'most_visited_pages',
            'visitors_by_device',
            'visitors_by_browser',
            'visitors_by_country',
            'daily_stats'
        ));
    }

    /**
     * Get visitor statistics as JSON (for AJAX requests)
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', '30'); // days
        
        $stats = [
            'total_visitors' => Visitor::getUniqueVisitorCount(),
            'total_page_views' => Visitor::getTotalPageViews(),
            'period_visitors' => Visitor::where('created_at', '>=', now()->subDays($period))->count(),
            'period_page_views' => Visitor::where('created_at', '>=', now()->subDays($period))->sum('visit_count'),
        ];

        return response()->json($stats);
    }

    /**
     * Get daily visitor statistics
     */
    public function getDailyStats(Request $request)
    {
        $days = $request->get('days', 30);
        $dailyStats = Visitor::getDailyStats($days);
        
        return response()->json($dailyStats);
    }

    /**
     * Get most visited pages
     */
    public function getMostVisitedPages(Request $request)
    {
        $limit = $request->get('limit', 10);
        $pages = Visitor::getMostVisitedPages($limit);
        
        return response()->json($pages);
    }

    /**
     * Get visitor statistics by device
     */
    public function getDeviceStats()
    {
        $deviceStats = Visitor::getVisitorsByDevice();
        
        return response()->json($deviceStats);
    }

    /**
     * Get visitor statistics by browser
     */
    public function getBrowserStats()
    {
        $browserStats = Visitor::getVisitorsByBrowser();
        
        return response()->json($browserStats);
    }

    /**
     * Get visitor statistics by country
     */
    public function getCountryStats()
    {
        $countryStats = Visitor::getVisitorsByCountry();
        
        return response()->json($countryStats);
    }

    /**
     * Get recent visitors
     */
    public function getRecentVisitors(Request $request)
    {
        $limit = $request->get('limit', 20);
        
        $recentVisitors = Visitor::select([
            'ip_address',
            'page_url',
            'page_title',
            'device_type',
            'browser',
            'country',
            'city',
            'created_at',
            'visit_count'
        ])
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();

        return response()->json($recentVisitors);
    }

    /**
     * Export visitor data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());
        $format = $request->get('format', 'csv');

        $visitors = Visitor::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($visitors);
        }

        return response()->json($visitors);
    }

    /**
     * Get public visitor statistics (for public display)
     */
    public function getPublicStats()
    {
        try {
            // Check if visitors table exists
            if (!Schema::hasTable('visitors')) {
                return response()->json([
                    'total_visitors' => 0,
                    'total_page_views' => 0,
                    'today_visitors' => 0,
                ]);
            }

            $stats = [
                'total_visitors' => Visitor::getUniqueVisitorCount(),
                'total_page_views' => Visitor::getTotalPageViews(),
                'today_visitors' => Visitor::whereDate('created_at', today())->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            // Return fallback data if there's an error
            return response()->json([
                'total_visitors' => 0,
                'total_page_views' => 0,
                'today_visitors' => 0,
            ]);
        }
    }

    /**
     * Export visitors to CSV
     */
    private function exportToCsv($visitors)
    {
        $filename = 'visitors_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($visitors) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'IP Address',
                'Page URL',
                'Page Title',
                'Device Type',
                'Browser',
                'OS',
                'Country',
                'City',
                'Visit Count',
                'Is Unique Visitor',
                'Created At',
                'Last Visit'
            ]);

            // CSV data
            foreach ($visitors as $visitor) {
                fputcsv($file, [
                    $visitor->id,
                    $visitor->ip_address,
                    $visitor->page_url,
                    $visitor->page_title,
                    $visitor->device_type,
                    $visitor->browser,
                    $visitor->os,
                    $visitor->country,
                    $visitor->city,
                    $visitor->visit_count,
                    $visitor->is_unique_visitor ? 'Yes' : 'No',
                    $visitor->created_at->format('Y-m-d H:i:s'),
                    $visitor->last_visit ? $visitor->last_visit->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
