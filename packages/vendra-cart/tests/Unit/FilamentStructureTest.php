<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Gate;
use Misaf\VendraCart\CartPlugin;
use Misaf\VendraCart\Filament\Resources\Carts\CartResource;
use Misaf\VendraCart\Filament\Resources\Carts\RelationManagers\CartItemsRelationManager;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraCart\Policies\CartPolicy;

it('registers a cart plugin and standalone resource', function (): void {
    expect(CartPlugin::make()->getId())->toBe('vendra-cart')
        ->and(CartResource::getModel())->toBe(Cart::class)
        ->and(CartResource::getCluster())->toBeNull()
        ->and(Gate::getPolicyFor(Cart::class))->toBeInstanceOf(CartPolicy::class)
        ->and(CartResource::getRelations())->toContain(CartItemsRelationManager::class)
        ->and(CartResource::getPages())->toHaveKeys(['index', 'view']);
});
