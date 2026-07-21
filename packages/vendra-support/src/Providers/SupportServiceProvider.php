<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Providers;

use Filament\Panel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Misaf\VendraSupport\Contracts\AttributeApiResolver;
use Misaf\VendraSupport\Contracts\AttributeResolver;
use Misaf\VendraSupport\Contracts\CurrencyResolver;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraSupport\Contracts\TagResolver;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraSupport\Filament\Concerns\ResolvesConfiguredPanels;
use Misaf\VendraSupport\Listeners\RunTenantSeeders;
use Misaf\VendraSupport\Support\NullAttributeApiResolver;
use Misaf\VendraSupport\Support\NullAttributeResolver;
use Misaf\VendraSupport\Support\NullCurrencyResolver;
use Misaf\VendraSupport\Support\NullSubscriptionCharger;
use Misaf\VendraSupport\Support\NullTagResolver;
use Misaf\VendraSupport\Support\NullTenantResolver;
use Misaf\VendraSupport\Support\TenantSeeders;
use Misaf\VendraSupport\Support\TenantTableRegistry;

final class SupportServiceProvider extends ServiceProvider
{
    use ResolvesConfiguredPanels;

    public function register(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'vendra-support');

        $this->mergeConfigFrom(__DIR__ . '/../../config/vendra-support.php', 'vendra-support');

        $this->app->singletonIf(TenantResolver::class, NullTenantResolver::class);
        $this->app->singletonIf(AttributeApiResolver::class, NullAttributeApiResolver::class);
        $this->app->singletonIf(AttributeResolver::class, NullAttributeResolver::class);
        $this->app->singletonIf(CurrencyResolver::class, NullCurrencyResolver::class);
        $this->app->singletonIf(TagResolver::class, NullTagResolver::class);
        $this->app->singletonIf(SubscriptionCharger::class, NullSubscriptionCharger::class);
        $this->app->singleton(TenantSeeders::class);
        $this->app->singleton(TenantTableRegistry::class);

        Panel::configureUsing(function (Panel $panel): void {
            if ( ! $this->shouldRegisterOnPanel($panel->getId(), 'vendra-support')) {
                return;
            }

            $panel->discoverClusters(
                in: __DIR__ . '/../Filament/Clusters',
                for: 'Misaf\\VendraSupport\\Filament\\Clusters',
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/vendra-support.php' => config_path('vendra-support.php'),
        ], 'vendra-support-config');

        Event::listen(TenantProvisioned::class, RunTenantSeeders::class);
    }
}
