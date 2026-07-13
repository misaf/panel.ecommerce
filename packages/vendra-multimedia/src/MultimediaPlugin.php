<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class MultimediaPlugin implements Plugin
{
    public const string ID = 'vendra-multimedia';

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

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(
                in: __DIR__ . '/Filament/Resources',
                for: 'Misaf\\VendraMultimedia\\Filament\\Resources',
            )
            ->discoverWidgets(
                in: __DIR__ . '/Filament/Widgets',
                for: 'Misaf\\VendraMultimedia\\Filament\\Widgets',
            );
    }

    public function boot(Panel $panel): void {}
}
