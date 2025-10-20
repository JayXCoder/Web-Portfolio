<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visitor;
use Illuminate\Support\Facades\Cache;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for admin routes and API routes
        if ($this->shouldSkipTracking($request)) {
            return $next($request);
        }

        $this->trackVisitor($request);

        return $next($request);
    }

    /**
     * Determine if we should skip tracking for this request
     */
    private function shouldSkipTracking(Request $request): bool
    {
        $path = $request->path();
        
        // Skip admin routes, API routes, and static assets
        return str_starts_with($path, 'admin') ||
               str_starts_with($path, 'api') ||
               str_contains($path, '.') ||
               $request->isMethod('POST') ||
               $request->isMethod('PUT') ||
               $request->isMethod('DELETE');
    }

    /**
     * Track the visitor
     */
    private function trackVisitor(Request $request): void
    {
        try {
            // Check if visitors table exists
            if (!\Schema::hasTable('visitors')) {
                return;
            }

            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            $pageUrl = $request->fullUrl();
            $referer = $request->header('referer');
            
            // Parse user agent for device and browser info
            $deviceInfo = $this->parseUserAgent($userAgent);
            
            // Check if this is a unique visitor (same IP in last 24 hours)
            $isUniqueVisitor = $this->isUniqueVisitor($ipAddress);
            
            // Check if visitor exists for today
            $existingVisitor = Visitor::where('ip_address', $ipAddress)
                ->where('page_url', $pageUrl)
                ->whereDate('created_at', today())
                ->first();

            if ($existingVisitor) {
                // Update existing visitor
                $existingVisitor->increment('visit_count');
                $existingVisitor->update([
                    'last_visit' => now(),
                    'user_agent' => $userAgent,
                    'referer' => $referer,
                ]);
            } else {
                // Create new visitor record
                Visitor::create([
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'referer' => $referer,
                    'page_url' => $pageUrl,
                    'page_title' => $this->getPageTitle($request),
                    'device_type' => $deviceInfo['device_type'],
                    'browser' => $deviceInfo['browser'],
                    'os' => $deviceInfo['os'],
                    'country' => $this->getCountryFromIP($ipAddress),
                    'city' => $this->getCityFromIP($ipAddress),
                    'last_visit' => now(),
                    'visit_count' => 1,
                    'is_unique_visitor' => $isUniqueVisitor,
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't break the request
            \Log::error('Visitor tracking error: ' . $e->getMessage());
        }
    }

    /**
     * Parse user agent to extract device and browser information
     */
    private function parseUserAgent(string $userAgent): array
    {
        $deviceType = 'desktop';
        $browser = 'Unknown';
        $os = 'Unknown';

        // Detect device type
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent)) {
            $deviceType = 'mobile';
        } elseif (preg_match('/Tablet|iPad/i', $userAgent)) {
            $deviceType = 'tablet';
        }

        // Detect browser
        if (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect OS
        if (preg_match('/Windows/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iOS/i', $userAgent)) {
            $os = 'iOS';
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os
        ];
    }

    /**
     * Check if visitor is unique (not seen in last 24 hours)
     */
    private function isUniqueVisitor(string $ipAddress): bool
    {
        $cacheKey = "visitor_{$ipAddress}";
        
        if (Cache::has($cacheKey)) {
            return false;
        }

        // Check database for recent visits
        $recentVisit = Visitor::where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if (!$recentVisit) {
            // Cache for 24 hours
            Cache::put($cacheKey, true, now()->addDay());
            return true;
        }

        return false;
    }

    /**
     * Get page title from request
     */
    private function getPageTitle(Request $request): ?string
    {
        $path = $request->path();
        
        // Handle root path specifically
        if ($path === '' || $path === '/') {
            return 'Home';
        }
        
        $titles = [
            'about' => 'About',
            'skills' => 'Skills',
            'projects' => 'Projects',
            'portfolio' => 'Portfolio',
            'contact' => 'Contact',
        ];

        return $titles[$path] ?? ucfirst($path);
    }

    /**
     * Get country from IP address (simplified - you might want to use a service like ipapi)
     */
    private function getCountryFromIP(string $ipAddress): ?string
    {
        // For localhost or private IPs
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1' || str_starts_with($ipAddress, '192.168.') || str_starts_with($ipAddress, '10.')) {
            return 'Local';
        }

        // You can integrate with services like ipapi.co, ipinfo.io, etc.
        // For now, return null
        return null;
    }

    /**
     * Get city from IP address (simplified - you might want to use a service like ipapi)
     */
    private function getCityFromIP(string $ipAddress): ?string
    {
        // You can integrate with services like ipapi.co, ipinfo.io, etc.
        // For now, return null
        return null;
    }
}
