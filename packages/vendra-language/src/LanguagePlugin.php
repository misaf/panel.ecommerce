<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class LanguagePlugin implements Plugin
{
    public const string ID = 'vendra-language';

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
        $panel->discoverResources(
            in: __DIR__ . '/Filament/Clusters/Resources',
            for: 'Misaf\\VendraLanguage\\Filament\\Clusters\\Resources',
        );
    }

    public function boot(Panel $panel): void {}
}
