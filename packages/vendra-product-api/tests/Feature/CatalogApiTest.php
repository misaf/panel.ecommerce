<?php

declare(strict_types=1);

use Misaf\VendraProduct\Database\Factories\ProductCategoryFactory;
use Misaf\VendraProduct\Database\Factories\ProductFactory;
use Misaf\VendraProduct\Database\Factories\ProductPriceFactory;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('filters catalog items and exposes group and price relationships', function (): void {
    $group = ProductCategoryFactory::new()->active()->create();
    $item = ProductFactory::new()->forCategory($group)->create(['in_stock' => true]);
    $price = ProductPriceFactory::new()->forProduct($item)->forCurrencyCode('USD')->create();
    ProductFactory::new()->forCategory($group)->create(['in_stock' => false]);

    $this->getJson("/api/catalog/products?inStock=1&groupId={$group->id}", ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('totalItems', 1)
        ->assertJsonPath('member.0.id', $item->id)
        ->assertJsonPath('member.0.group.id', $group->id)
        ->assertJsonPath('member.0.prices.0.id', $price->id);

    $this->getJson("/api/catalog/product-prices?currency=USD&itemId={$item->id}", ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('totalItems', 1)
        ->assertJsonPath('member.0.item.id', $item->id);
});

it('serves catalog items in the JSON:API envelope', function (): void {
    $group = ProductCategoryFactory::new()->active()->create();
    $item = ProductFactory::new()->forCategory($group)->create(['in_stock' => true]);
    $price = ProductPriceFactory::new()->forProduct($item)->forCurrencyCode('USD')->create();

    $this->getJson("/api/catalog/products?inStock=1&groupId={$group->id}", ['Accept' => 'application/vnd.api+json'])
        ->assertOk()
        ->assertHeader('content-type', 'application/vnd.api+json; charset=utf-8')
        ->assertJsonPath('meta.totalItems', 1)
        ->assertJsonPath('data.0.type', 'Product')
        ->assertJsonPath('data.0.id', "/api/catalog/products/{$item->id}")
        ->assertJsonPath('data.0.attributes.id', $item->id)
        ->assertJsonPath('data.0.attributes.inStock', true)
        ->assertJsonPath('data.0.attributes.group.id', $group->id)
        ->assertJsonPath('data.0.attributes.prices.0.id', $price->id);
});
