<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Misaf\VendraStore\Contracts\StoreOwnerResolver;
use Misaf\VendraStore\Models\Store;
use Misaf\VendraStore\Models\StoreDomain;
use Misaf\VendraStore\Services\StoreDomainFinder;
use Misaf\VendraStore\Support\NullStoreOwnerResolver;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraTenant\Contracts\HostTenantFinder;
use Misaf\VendraTenant\Contracts\TenantContract;

/*
 | Store IS the tenant. Nothing in here should ever need a second row, a
 | `tenants` table, or a `Store -> Tenant` hop.
 */
it('is the application tenant rather than pointing at one', function (): void {
    $store = Store::factory()->active()->create(['name' => 'Acme Flowers', 'slug' => 'acme']);

    expect($store)->toBeInstanceOf(TenantContract::class)
        ->and(Config::get('vendra-tenant.model'))->toBe(Store::class)
        ->and(Config::get('multitenancy.tenant_model'))->toBe(Store::class)
        ->and(app(TenantResolver::class)->modelClass())->toBe(Store::class)
        ->and($store->getTenantKey())->toBe($store->id)
        ->and($store->getTenantName())->toBe('Acme Flowers')
        ->and($store->getTenantSlug())->toBe('acme')
        ->and(method_exists($store, 'tenant'))->toBeFalse();
});

it('becomes the current tenant and restores the previous context', function (): void {
    $store = Store::factory()->active()->create();
    $resolver = app(TenantResolver::class);

    expect($resolver->current())->toBeNull();

    $seen = $resolver->execute($store->getKey(), fn(): ?int => $resolver->currentId());

    expect($seen)->toBe($store->getKey())
        ->and($resolver->current())->toBeNull();
});

it('answers the tenancy engine host-resolution port', function (): void {
    expect(app(HostTenantFinder::class))->toBeInstanceOf(StoreDomainFinder::class)
        ->and(Config::get('multitenancy.tenant_finder'))->toBe(StoreDomainFinder::class);
});

it('resolves itself from one of its own domains', function (): void {
    $store = Store::factory()->active()->create(['slug' => 'acme']);
    StoreDomain::factory()->for($store)->create(['name' => 'acme.example.com', 'active' => true]);

    expect(app(HostTenantFinder::class)->findForHost('acme.example.com')?->getKey())->toBe($store->getKey())
        ->and(app(HostTenantFinder::class)->findForOrigin('https://acme.example.com')?->getKey())->toBe($store->getKey());
});

it('keeps its domains keyed by store_id and scoped to itself', function (): void {
    $first = Store::factory()->active()->create();
    $second = Store::factory()->active()->create();

    StoreDomain::factory()->for($first)->create(['name' => 'first.example.com', 'active' => true]);
    StoreDomain::factory()->for($second)->create(['name' => 'second.example.com', 'active' => true]);

    expect($first->domains()->pluck('name')->all())->toBe(['first.example.com'])
        ->and($second->domains()->pluck('name')->all())->toBe(['second.example.com']);

    $visible = app(TenantResolver::class)->execute(
        $first->getKey(),
        fn(): array => StoreDomain::query()->pluck('name')->all(),
    );

    expect($visible)->toBe(['first.example.com']);
});

it('has no billing owner until a reseller domain supplies one', function (): void {
    $store = Store::factory()->active()->create();

    expect($store->reseller_id)->toBeNull();

    /*
     | The reseller package binds the real resolver; the store package only ever
     | asks through the port, which is why it stays installable without it.
     */
    app()->bind(StoreOwnerResolver::class, NullStoreOwnerResolver::class);

    expect(app(StoreOwnerResolver::class)->find(1))->toBeNull();
});
