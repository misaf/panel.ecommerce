<?php

declare(strict_types=1);

use LaravelJsonApi\Contracts\Schema\Schema;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraCart\Models\CartItem;
use Misaf\VendraCartApi\JsonApi\V1\CartItems\CartItemResource;
use Misaf\VendraCartApi\JsonApi\V1\Carts\CartResource;

it('serializes cart attributes without exposing raw owner morph columns', function (): void {
    $cart = new Cart([
        'token' => '0197fc6b-2880-7000-8000-000000000001',
    ]);

    $attributes = iterator_to_array(
        (new CartResource(Mockery::mock(Schema::class), $cart))->attributes(request()),
    );

    expect($attributes)->toHaveKeys(['token', 'owner_label', 'expires_at', 'created_at', 'updated_at'])
        ->not->toHaveKeys(['owner_type', 'owner_id']);
});

it('serializes sellable identity without owning catalog data', function (): void {
    $item = new CartItem([
        'sellable_type' => 'product',
        'sellable_id'   => 42,
        'quantity'      => 2,
        'metadata'      => ['color' => 'blue'],
    ]);

    $attributes = iterator_to_array(
        (new CartItemResource(Mockery::mock(Schema::class), $item))->attributes(request()),
    );

    expect($attributes)
        ->sellable_type->toBe('product')
        ->sellable_id->toBe(42)
        ->quantity->toBe(2)
        ->metadata->toBe(['color' => 'blue']);
});
