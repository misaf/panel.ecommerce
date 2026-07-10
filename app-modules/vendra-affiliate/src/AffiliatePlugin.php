<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class AffiliatePlugin implements Plugin
{
    public const string ID = 'vendra-affiliate';

    public function getId(): string
    {
        return self::ID;
    }

    public static function make(): static
    {
        /** @var static $plugin */
        $plugin = app(static::class);

        return $plugin;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(
                in: __DIR__ . '/Filament/Resources',
                for: 'Misaf\\VendraAffiliate\\Filament\\Resources',
            )
            ->discoverWidgets(
                in: __DIR__ . '/Filament/Widgets',
                for: 'Misaf\\VendraAffiliate\\Filament\\Widgets',
            );
    }

    public function boot(Panel $panel): void {}
}
