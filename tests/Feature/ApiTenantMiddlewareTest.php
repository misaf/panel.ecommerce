<?php

declare(strict_types=1);

use Misaf\VendraProduct\Database\Factories\ProductCategoryFactory;
use Misaf\VendraProduct\Database\Factories\ProductFactory;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;

beforeEach(function (): void {
    config()->set('app.url', 'https://vendra.test');
    config()->set('vendra-tenant.central_host', 'vendra.test');
});

it('rejects API resource requests that do not resolve a tenant', function (): void {
    $this->getJson('https://vendra.test/api/catalog/products', [
        'Accept' => 'application/vnd.api+json',
    ])->assertNotFound();
});

it('resolves and isolates API resources by tenant domain', function (): void {
    $firstTenant = Tenant::factory()->active()->create(['slug' => 'flowers']);
    TenantDomain::factory()->for($firstTenant)->create([
        'name'   => 'flowers.example.com',
        'active' => true,
    ]);

    $firstTenant->execute(function (): void {
        $category = ProductCategoryFactory::new()->active()->create();
        ProductFactory::new()->forCategory($category)->create([
            'name' => ['en' => 'Visible Rose'],
        ]);
    });

    $secondTenant = Tenant::factory()->active()->create(['slug' => 'other']);
    $secondTenant->execute(function (): void {
        $category = ProductCategoryFactory::new()->active()->create();
        ProductFactory::new()->forCategory($category)->create([
            'name' => ['en' => 'Hidden Tulip'],
        ]);
    });

    forgetCurrentTestTenant();

    $this->getJson('https://admin.flowers.example.com/api/catalog/products', [
        'Accept'          => 'application/vnd.api+json',
        'Accept-Language' => 'en',
    ])
        ->assertSuccessful()
        ->assertJsonPath('meta.totalItems', 1)
        ->assertJsonPath('data.0.attributes.name.en', 'Visible Rose')
        ->assertJsonMissing(['en' => 'Hidden Tulip']);

    expect(currentTestTenant())->toBeNull();
});
