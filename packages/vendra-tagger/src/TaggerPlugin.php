<?php

declare(strict_types=1);

namespace Misaf\VendraTagger;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class TaggerPlugin implements Plugin
{
    public const string ID = 'vendra-tagger';

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
            in: __DIR__ . '/Filament/Resources',
            for: 'Misaf\\VendraTagger\\Filament\\Resources',
        );
    }

    public function boot(Panel $panel): void {}
}
