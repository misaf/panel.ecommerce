<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

/*
 | The Store is the concrete tenant, so it sits directly on the tenancy engine
 | and below everything that owns or administers stores. Reseller ownership
 | reaches it through `Contracts\StoreOwnerResolver`, which is what keeps this
 | package installable — and this suite honest — without the reseller domain.
 */
arch('the store domain does not depend on its owners or administrators')
    ->expect('Misaf\VendraStore')->not->toUse([
        'Misaf\VendraReseller',
        'Misaf\VendraConsole',
    ]);

arch('the store domain does not reach into other business domains')
    ->expect('Misaf\VendraStore')->not->toUse([
        'Misaf\VendraProduct',
        'Misaf\VendraBlog',
        'Misaf\VendraCart',
        'Misaf\VendraAttribute',
        'Misaf\VendraCurrency',
        'Misaf\VendraTransaction',
        'Misaf\VendraNewsletter',
        'Misaf\VendraFaq',
        'Misaf\VendraCustomPage',
        'Misaf\VendraAffiliate',
        'Misaf\VendraTagger',
    ]);

/*
 | Engine-API talk is `vendra-container`'s business. The store owns the intent —
 | "this store should have a storefront" — and everything that speaks to a
 | runtime goes through `Contracts\StorefrontProvisioner` and its one adapter,
 | `Services\ContainerStorefrontProvisioner`. Actions and jobs describe what
 | should exist; they never hold a runtime client.
 */
arch('the store domain stays behind the storefront provisioning port')
    ->expect(['Misaf\VendraStore\Actions', 'Misaf\VendraStore\Jobs', 'Misaf\VendraStore\Models'])
    ->not->toUse([
        'Misaf\VendraContainer\Contracts\ContainerRuntime',
        'Misaf\VendraContainer\Http\EngineApiClient',
    ]);
