<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class ActivityLogPlugin implements Plugin
{
    public const string ID = 'vendra-activity-log';

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
                in: __DIR__ . '/Filament/Clusters/Resources',
                for: 'Misaf\\VendraActivityLog\\Filament\\Clusters\\Resources',
            )
            ->discoverWidgets(
                in: __DIR__ . '/Filament/Widgets',
                for: 'Misaf\\VendraActivityLog\\Filament\\Widgets',
            );
    }

    public function boot(Panel $panel): void {}
}
