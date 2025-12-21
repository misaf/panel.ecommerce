<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Providers;

use Illuminate\Support\ServiceProvider;

final class UserLevelServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'user-level');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/user-level'),
        ], 'user-level-lang');
    }
}
