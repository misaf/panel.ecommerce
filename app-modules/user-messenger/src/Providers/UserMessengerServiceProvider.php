<?php

declare(strict_types=1);

namespace Misaf\UserMessenger\Providers;

use Illuminate\Support\ServiceProvider;

final class UserMessengerServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'user-messenger');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/user-messenger'),
        ], 'user-messenger-lang');
    }
}
