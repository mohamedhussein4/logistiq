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
            // Admin routes
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            // Logistics company routes
            Route::middleware('web')
                ->group(base_path('routes/logistics.php'));

            // Service company routes
            Route::middleware('web')
                ->group(base_path('routes/service-company.php'));

            // User routes
            Route::middleware('web')
                ->group(base_path('routes/user.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'logistics' => \App\Http\Middleware\LogisticsMiddleware::class,
            'service_company' => \App\Http\Middleware\ServiceCompanyMiddleware::class,
            'regular_user' => \App\Http\Middleware\RegularUserMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
