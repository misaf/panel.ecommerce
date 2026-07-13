<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers cart API routes only through tenant-scoped carts', function (): void {
    $uris = collect(Route::getRoutes()->getRoutes())
        ->map(fn(Illuminate\Routing\Route $route): string => $route->uri());

    expect(Route::has('vendra-cart.carts.index'))->toBeTrue()
        ->and(route('vendra-cart.carts.index', [], false))->toBe('/v1/carts')
        ->and($uris)->toContain('v1/carts/{cart}/items')
        ->and($uris)->toContain('v1/carts/{cart}/relationships/items')
        ->and($uris)->not->toContain('v1/cart-items')
        ->and($uris)->not->toContain('v1/cart-items/{cart_item}')
        ->and(Route::has('vendra-cart.cart-items.index'))->toBeFalse()
        ->and(Route::has('vendra-cart.cart-items.show'))->toBeFalse();
});
