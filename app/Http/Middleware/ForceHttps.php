<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we're behind a proxy (like Cloudflare, load balancer, etc.)
        $isSecure = $request->secure() || 
                    $request->header('x-forwarded-proto') === 'https' ||
                    $request->header('x-forwarded-scheme') === 'https';
        
        // Force HTTPS for admin routes only if not already secure
        if ($request->is('admin*') && !$isSecure) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
