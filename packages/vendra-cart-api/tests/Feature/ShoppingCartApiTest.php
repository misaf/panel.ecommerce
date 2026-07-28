<?php

declare(strict_types=1);

use Misaf\VendraCart\Database\Factories\CartFactory;
use Misaf\VendraCart\Database\Factories\CartItemFactory;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('requires authentication and exposes only carts owned by the user', function (): void {
    $user = createTestUser();
    $otherUser = createTestUser();
    $cart = CartFactory::new()->forOwner($user)->create();
    $line = CartItemFactory::new()->forCart($cart)->create(['quantity' => 3]);
    $hidden = CartFactory::new()->forOwner($otherUser)->create();

    $this->getJson('/api/sales/carts')->assertUnauthorized();

    $this->actingAs($user)
        ->getJson('/api/sales/carts', ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('totalItems', 1)
        ->assertJsonPath('member.0.id', $cart->id)
        ->assertJsonPath('member.0.lines.0.id', $line->id)
        ->assertJsonMissing(['id' => $hidden->id]);
});

it('denies access to a cart owned by another user', function (): void {
    $user = createTestUser();
    $cart = CartFactory::new()->forOwner(createTestUser())->create();

    $this->actingAs($user)
        ->getJson("/api/sales/carts/{$cart->id}", ['Accept' => 'application/ld+json'])
        ->assertNotFound();
});
