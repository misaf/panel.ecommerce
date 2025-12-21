<?php

declare(strict_types=1);

namespace Misaf\User\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Misaf\User\Services\UserService;

final class UserServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('user-service', fn(Application $app) => new UserService());
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'user');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/user'),
        ], 'user-lang');
    }
}
