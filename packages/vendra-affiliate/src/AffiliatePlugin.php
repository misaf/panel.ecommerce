<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Config;
use Misaf\VendraAffiliate\Filament\Widgets\AffiliateOverviewWidget;
use Misaf\VendraAffiliate\Filament\Widgets\UserAffiliateOverviewWidget;

final class AffiliatePlugin implements Plugin
{
    public const string ID = 'vendra-affiliate';

    protected string|Closure|null $navigationGroup = null;

    public function getId(): string
    {
        return self::ID;
    }

    public static function make(): static
    {
        /** @var static $plugin */
        $plugin = app(self::class);

        return $plugin;
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(self::ID);

        return $plugin;
    }

    public function navigationGroup(string|Closure|null $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): string
    {
        $group = $this->navigationGroup ?? Config::get('vendra-affiliate.navigation_group');

        if ($group instanceof Closure) {
            $group = $group();
        }

        if ( ! is_string($group) || '' === $group) {
            $group = 'vendra-support::navigation.groups.Marketing';
        }

        return (string) __($group);
    }

    public function register(Panel $panel): void
    {
        if ($this->isConfiguredPanel($panel, 'vendra-affiliate.panels')) {
            $panel->discoverClusters(
                in: __DIR__ . '/Filament/Clusters',
                for: 'Misaf\\VendraAffiliate\\Filament\\Clusters',
            );

            $panel->widgets([
                AffiliateOverviewWidget::class,
            ]);
        }

        if ($this->isConfiguredPanel($panel, 'vendra-affiliate.user_panels')) {
            $panel->widgets([
                UserAffiliateOverviewWidget::class,
            ]);
        }
    }

    public function boot(Panel $panel): void {}

    private function isConfiguredPanel(Panel $panel, string $configKey): bool
    {
        return in_array($panel->getId(), Config::array($configKey), true);
    }
}
