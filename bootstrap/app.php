<?php

declare(strict_types=1);

use App\Http\Middleware\AddRequestContext;
use App\Http\Middleware\UseRequestUrl;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend([
            AddRequestContext::class,
            UseRequestUrl::class,
        ]);

        $middleware->preventRequestForgery(except: [
            '/livewire/*',
            '/webhooks/coinpayments',
            '/webhooks/resend',
            '*/oauth/callback/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
