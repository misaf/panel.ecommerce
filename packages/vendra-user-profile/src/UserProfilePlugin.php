<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class UserProfilePlugin implements Plugin
{
    public function getId(): string
    {
        return 'vendra-user-profile';
    }

    public static function make(): static
    {
        /** @var static $plugin */
        $plugin = app(static::class);

        return $plugin;
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(
            in: __DIR__ . '/Filament/Clusters',
            for: 'Misaf\\VendraUserProfile\\Filament\\Clusters',
        );
    }

    public function boot(Panel $panel): void {}
}
