<?php

declare(strict_types=1);

namespace Misaf\Language\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Misaf\Language\Services\LanguageService;

final class LanguageDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('language-service', fn(): LanguageService => new LanguageService());
    }

    /**
     * @return array<string>
     */
    public function provides(): array
    {
        return ['language-service'];
    }
}
