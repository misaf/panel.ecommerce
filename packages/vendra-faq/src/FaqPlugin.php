<?php

declare(strict_types=1);

namespace Misaf\VendraFaq;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class FaqPlugin implements Plugin
{
    public const string ID = 'vendra-faq';

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
        $panel->discoverResources(
            in: __DIR__ . '/Filament/Clusters/Resources',
            for: 'Misaf\\VendraFaq\\Filament\\Clusters\\Resources',
        );
    }

    public function boot(Panel $panel): void {}
}
