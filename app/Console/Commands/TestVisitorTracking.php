<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visitor;

class TestVisitorTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitors:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test visitor tracking system and display statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Visitor Tracking System...');
        $this->newLine();

        // Display current statistics
        $totalVisitors = Visitor::getUniqueVisitorCount();
        $totalPageViews = Visitor::getTotalPageViews();
        $todayVisitors = Visitor::whereDate('created_at', today())->count();
        $thisWeekVisitors = Visitor::where('created_at', '>=', now()->subWeek())->count();

        $this->info('📊 Current Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Unique Visitors', number_format($totalVisitors)],
                ['Total Page Views', number_format($totalPageViews)],
                ['Today\'s Visitors', number_format($todayVisitors)],
                ['This Week\'s Visitors', number_format($thisWeekVisitors)],
            ]
        );

        // Show recent visitors
        $recentVisitors = Visitor::orderBy('created_at', 'desc')->take(5)->get();
        
        if ($recentVisitors->count() > 0) {
            $this->newLine();
            $this->info('👥 Recent Visitors:');
            $visitorData = [];
            foreach ($recentVisitors as $visitor) {
                $visitorData[] = [
                    $visitor->ip_address,
                    $visitor->page_title ?: 'Unknown',
                    $visitor->device_type ?: 'Unknown',
                    $visitor->browser ?: 'Unknown',
                    $visitor->created_at->format('Y-m-d H:i:s')
                ];
            }
            
            $this->table(
                ['IP Address', 'Page', 'Device', 'Browser', 'Visited At'],
                $visitorData
            );
        } else {
            $this->warn('No visitors recorded yet. Try visiting your website to generate some data!');
        }

        // Show most visited pages
        $mostVisited = Visitor::getMostVisitedPages(5);
        if ($mostVisited->count() > 0) {
            $this->newLine();
            $this->info('🔥 Most Visited Pages:');
            $pageData = [];
            foreach ($mostVisited as $page) {
                $pageData[] = [
                    $page->page_title ?: $page->page_url,
                    number_format($page->total_views)
                ];
            }
            
            $this->table(
                ['Page', 'Views'],
                $pageData
            );
        }

        $this->newLine();
        $this->info('✅ Visitor tracking system is working!');
        $this->info('💡 Visit your website to generate visitor data.');
        $this->info('🔗 Admin panel: /admin/visitors');
    }
}
