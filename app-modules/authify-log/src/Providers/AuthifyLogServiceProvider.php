<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Providers;

use Illuminate\Support\ServiceProvider;

final class AuthifyLogServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'authify-log');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/authify-log'),
        ], 'authify-log-lang');
    }
}
