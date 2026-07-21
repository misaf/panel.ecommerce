<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Route;
use Misaf\VendraAttribute\Database\Factories\AttributeValueFactory;
use Misaf\VendraProduct\Database\Factories\ProductCategoryFactory;
use Misaf\VendraProduct\Database\Factories\ProductFactory;

it('registers attribute-value relationship routes for products and product categories', function (): void {
    expect(Route::has('vendra-product.products.attributeValues.show'))->toBeTrue()
        ->and(Route::has('vendra-product.products.selectedAttributeValues.show'))->toBeTrue()
        ->and(Route::has('vendra-product.product-categories.attributeValues.show'))->toBeTrue();
})->skip(
    fn(): bool => ! InstalledVersions::isInstalled('misaf/vendra-attribute-api'),
    'vendra-attribute-api is not installed',
);

it('serves category-owned attribute values inherited by products', function (): void {
    makeCurrentTestTenant();

    $productCategory = ProductCategoryFactory::new()->create();
    $product = ProductFactory::new()->forCategory($productCategory)->create();

    $attributeValue = AttributeValueFactory::new()->forAttributable($productCategory)->create();

    $this->getJson(
        '/v1/product-categories/' . $productCategory->id . '/attribute-values',
        ['Accept' => 'application/vnd.api+json'],
    )
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $attributeValue->id);

    $this->getJson(
        '/v1/products/' . $product->id . '/attribute-values',
        ['Accept' => 'application/vnd.api+json'],
    )
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $attributeValue->id);
})->skip(
    fn(): bool => ! InstalledVersions::isInstalled('misaf/vendra-attribute-api'),
    'vendra-attribute-api is not installed',
);

it('serves the attribute values a product selected from its category set', function (): void {
    makeCurrentTestTenant();

    $productCategory = ProductCategoryFactory::new()->create();
    $product = ProductFactory::new()->forCategory($productCategory)->create();

    $selected = AttributeValueFactory::new()->forAttributable($productCategory)->create();
    AttributeValueFactory::new()->forAttributable($productCategory)->create();

    $product->selectedAttributeValues()->attach($selected);

    $this->getJson(
        '/v1/products/' . $product->id . '/selected-attribute-values',
        ['Accept' => 'application/vnd.api+json'],
    )
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $selected->id);
})->skip(
    fn(): bool => ! InstalledVersions::isInstalled('misaf/vendra-attribute-api'),
    'vendra-attribute-api is not installed',
);
