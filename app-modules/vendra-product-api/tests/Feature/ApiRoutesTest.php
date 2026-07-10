<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers product api read routes', function (): void {
    expect(Route::has('vendra-product.products.index'))->toBeTrue()
        ->and(Route::has('vendra-product.product-categories.index'))->toBeTrue()
        ->and(Route::has('vendra-product.product-prices.index'))->toBeTrue()
        ->and(route('vendra-product.products.index', [], false))->toBe('/v1/products')
        ->and(route('vendra-product.product-categories.index', [], false))->toBe('/v1/product-categories')
        ->and(route('vendra-product.product-prices.index', [], false))->toBe('/v1/product-prices');
});
