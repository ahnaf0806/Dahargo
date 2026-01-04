<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PastikanMejaTerpilih;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureSuperadmin;
use App\Http\Middleware\EnsureUserActive;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'pastikan.meja' => PastikanMejaTerpilih::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'pastikan.meja' => PastikanMejaTerpilih::class,
            'admin.only' => EnsureAdmin::class,
            'superadmin.only' => EnsureSuperadmin::class,
            'user.active' => EnsureUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
