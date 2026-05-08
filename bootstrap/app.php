<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/owner.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'owner' => \App\Http\Middleware\EnsureUserIsOwner::class,
        ]);

        // Route unauthenticated users to the right login page based on the
        // URL prefix they hit, so /admin/* doesn't dump customers on /login
        // and /owner/* doesn't either.
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin', 'admin/*')) {
                return route('admin.login');
            }
            if ($request->is('owner', 'owner/*')) {
                return route('owner.login');
            }

            return route('login');
        });

        // Add security headers middleware globally
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
