<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CustomAuthenticate;
use App\Http\Middleware\UpdateLastActivity; // Import your middleware
use App\Http\Middleware\ClearExpiredSession; // Import your middleware
use App\Http\Middleware\CheckAdmin; // Import your middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        $middleware->alias([
            'UpdateLastActivity'=> \App\Http\Middleware\UpdateLastActivity::class,
            'CustomAuthenticate' => \App\Http\Middleware\CustomAuthenticate::class,  // Alias for custom authentication middleware
            'ClearExpiredSession' => \App\Http\Middleware\ClearExpiredSession::class,  // Alias for custom authentication middleware
            'CheckAdmin' => \App\Http\Middleware\CheckAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle exceptions if needed
    })
    ->create();
