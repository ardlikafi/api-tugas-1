<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Mungkin sudah ada middleware lain yang didaftarkan di sini
        // seperti $middleware->web(...) atau $middleware->api(...)
    
        // Tambahkan alias Anda di sini:
        $middleware->alias([
            'api.key' => \App\Http\Middleware\ApiKeyMiddleware::class,
            // Tambahkan alias lain jika perlu
        ]);
    
        // ... (Mungkin ada konfigurasi lain di bawahnya)
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
