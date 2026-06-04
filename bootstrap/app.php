<?php

use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\TrackVisitor;
use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Reverse proxy (Nginx Proxy Manager, Traefik, Cloudflare, etc.) terminates TLS
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'force.https' => ForceHttps::class,
            'admin' => AdminMiddleware::class,
            'track.visitor' =>TrackVisitor::class,
        ]);

        // Apply visitor tracking to all web routes except admin routes
        $middleware->web(append: [
            TrackVisitor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
