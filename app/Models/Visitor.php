<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'referer',
        'page_url',
        'page_title',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'last_visit',
        'visit_count',
        'is_unique_visitor'
    ];

    protected $casts = [
        'last_visit' => 'datetime',
        'is_unique_visitor' => 'boolean',
        'visit_count' => 'integer'
    ];

    /**
     * Get the total number of unique visitors
     */
    public static function getUniqueVisitorCount()
    {
        return self::where('is_unique_visitor', true)->count();
    }

    /**
     * Get the total number of page views
     */
    public static function getTotalPageViews()
    {
        return self::sum('visit_count');
    }

    /**
     * Get visitors for a specific date range
     */
    public static function getVisitorsByDateRange($startDate, $endDate)
    {
        return self::whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get most visited pages
     */
    public static function getMostVisitedPages($limit = 10)
    {
        return self::selectRaw('page_url, page_title, SUM(visit_count) as total_views')
            ->groupBy('page_url', 'page_title')
            ->orderBy('total_views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get visitor statistics by device type
     */
    public static function getVisitorsByDevice()
    {
        return self::selectRaw('device_type, COUNT(*) as count')
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->get();
    }

    /**
     * Get visitor statistics by browser
     */
    public static function getVisitorsByBrowser()
    {
        return self::selectRaw('browser, COUNT(*) as count')
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get visitor statistics by country
     */
    public static function getVisitorsByCountry()
    {
        return self::selectRaw('country, COUNT(*) as count')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get daily visitor statistics
     */
    public static function getDailyStats($days = 30)
    {
        return self::selectRaw('DATE(created_at) as date, COUNT(*) as visitors, SUM(visit_count) as page_views')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
}
