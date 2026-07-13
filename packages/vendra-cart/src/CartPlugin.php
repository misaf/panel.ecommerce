<?php

declare(strict_types=1);

namespace Misaf\VendraCart;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Misaf\VendraCart\Filament\Resources\Carts\CartResource;

final class CartPlugin implements Plugin
{
    public const string ID = 'vendra-cart';

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
        $panel->resources([
            CartResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
