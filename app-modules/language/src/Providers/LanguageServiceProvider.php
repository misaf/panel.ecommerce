<?php

declare(strict_types=1);

namespace Misaf\Language\Providers;

use Illuminate\Support\ServiceProvider;

final class LanguageServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/translation-loader.php', 'translation-loader');
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'language');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/language'),
        ], 'language-lang');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
