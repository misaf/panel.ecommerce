<?php

declare(strict_types=1);

use Misaf\VendraProduct\Database\Factories\ProductCategoryFactory;
use Misaf\VendraProduct\Database\Factories\ProductFactory;
use Misaf\VendraProduct\Database\Factories\ProductPriceFactory;
use Misaf\VendraProduct\Models\ProductPrice;

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
        ->assertJsonPath('member.0.productCategory', "/api/catalog/product-categories/{$group->id}")
        ->assertJsonPath('member.0.productPrices.0', "/api/catalog/product-prices/{$price->id}");

    $this->getJson("/api/catalog/product-prices?currency=USD&itemId={$item->id}", ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('totalItems', 1)
        ->assertJsonPath('member.0.item.id', $item->id);
});

it('serves catalog items in the JSON:API envelope with relationships and includes', function (): void {
    $group = ProductCategoryFactory::new()->active()->create();
    $item = ProductFactory::new()->forCategory($group)->create(['in_stock' => true]);
    $price = ProductPriceFactory::new()->forProduct($item)->forCurrencyCode('USD')->create();

    $this->getJson("/api/catalog/products?inStock=1&groupId={$group->id}&include=productCategory,productPrices", ['Accept' => 'application/vnd.api+json'])
        ->assertOk()
        ->assertHeader('content-type', 'application/vnd.api+json; charset=utf-8')
        ->assertJsonPath('meta.totalItems', 1)
        ->assertJsonPath('data.0.type', 'Product')
        ->assertJsonPath('data.0.id', "/api/catalog/products/{$item->id}")
        ->assertJsonPath('data.0.attributes.id', $item->id)
        ->assertJsonPath('data.0.attributes.inStock', true)
        ->assertJsonPath('data.0.relationships.productCategory.data.id', "/api/catalog/product-categories/{$group->id}")
        ->assertJsonPath('data.0.relationships.productPrices.data.0.id', "/api/catalog/product-prices/{$price->id}")
        ->assertJsonPath('included.0.attributes.id', $group->id)
        ->assertJsonPath('included.1.attributes.amount', ProductPrice::toMajorUnits('USD', (int) $price->price->getAmount()))
        ->assertJsonPath('included.1.attributes.formatted', $price->formattedPrice());
});

it('looks up a product by its translatable slug and by token', function (): void {
    $group = ProductCategoryFactory::new()->active()->create();
    $locale = app()->getLocale();
    $item = ProductFactory::new()->forCategory($group)->create([
        'name' => [$locale => 'Red Rose'],
        'slug' => [$locale => 'red-rose'],
    ]);
    ProductFactory::new()->forCategory($group)->create([
        'name' => [$locale => 'Tulip'],
        'slug' => [$locale => 'tulip'],
    ]);
    $headers = [
        'Accept'          => 'application/vnd.api+json',
        'Accept-Language' => $locale,
    ];

    $this->getJson('/api/catalog/products?slug=red-rose', $headers)
        ->assertOk()
        ->assertJsonPath('meta.totalItems', 1)
        ->assertJsonPath('data.0.attributes.id', $item->id);

    $this->getJson("/api/catalog/products?token={$item->token}", $headers)
        ->assertOk()
        ->assertJsonPath('meta.totalItems', 1)
        ->assertJsonPath('data.0.attributes.id', $item->id);

    $this->getJson('/api/catalog/products?search=rose', $headers)
        ->assertOk()
        ->assertJsonPath('meta.totalItems', 1)
        ->assertJsonPath('data.0.attributes.id', $item->id);
});

it('sorts catalog items with the API Platform order filter', function (string $direction, bool $descending): void {
    $group = ProductCategoryFactory::new()->active()->create();
    $firstItem = ProductFactory::new()->forCategory($group)->create();
    $secondItem = ProductFactory::new()->forCategory($group)->create();
    $expectedIds = $descending
        ? [$secondItem->id, $firstItem->id]
        : [$firstItem->id, $secondItem->id];

    $this->getJson("/api/catalog/products?sort[id]={$direction}", ['Accept' => 'application/vnd.api+json'])
        ->assertOk()
        ->assertJsonPath('data.0.attributes.id', $expectedIds[0])
        ->assertJsonPath('data.1.attributes.id', $expectedIds[1]);
})->with([
    'newest first' => ['desc', true],
    'oldest first' => ['asc', false],
]);

it('validates and applies the random order filter', function (): void {
    $group = ProductCategoryFactory::new()->active()->create();
    ProductFactory::new()->forCategory($group)->create();

    $this->getJson('/api/catalog/products?random=1', ['Accept' => 'application/vnd.api+json'])
        ->assertOk()
        ->assertJsonPath('meta.totalItems', 1);

    $this->getJson('/api/catalog/products?random=invalid', ['Accept' => 'application/vnd.api+json'])
        ->assertUnprocessable();
});
