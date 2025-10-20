<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access this area.');
        }

        // Check if user is an admin
        if (!auth()->user()->isAdmin()) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => auth()->user()->id,
                'email' => auth()->user()->email,
                'role' => auth()->user()->role,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);
            
            // Logout the user and redirect to access denied page
            auth()->logout();
            return redirect()->route('admin.access-denied');
        }

        // Check if user is active
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}