<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Misaf\Affiliate\Listeners\AffiliateSubscriber;
use Misaf\Affiliate\Services\AffiliateService;

final class AffiliateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('affiliate-service', fn(Application $app) => new AffiliateService());
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'affiliate');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/affiliate'),
        ], 'affiliate-lang');

        Event::subscribe(AffiliateSubscriber::class);
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            'affiliate-service',
        ];
    }
}
