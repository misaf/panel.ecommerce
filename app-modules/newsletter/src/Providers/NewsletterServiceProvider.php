<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Providers;

use Illuminate\Support\ServiceProvider;

final class NewsletterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/newsletter.php', 'newsletter');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'newsletter');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'newsletter');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/newsletter'),
        ], 'newsletter-lang');

        $this->publishes([
            __DIR__ . '/../../config/newsletter.php' => config_path('newsletter.php'),
        ], 'newsletter-config');
    }
}
