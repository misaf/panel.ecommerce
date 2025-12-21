<?php

declare(strict_types=1);

namespace Misaf\Tag\Providers;

use Illuminate\Support\ServiceProvider;

final class TagServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/tags.php', 'tags');
        $this->mergeConfigFrom(__DIR__ . '/../../config/eloquent-sortable.php', 'eloquent-sortable');
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'tag');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/tag'),
        ], 'tag-lang');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
