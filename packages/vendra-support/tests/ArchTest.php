<?php

declare(strict_types=1);

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Misaf\VendraSupport\Tenancy\Events\TenantProvisioned;

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('support never depends on a concrete tenant provider')
    ->expect('Misaf\VendraSupport')
    ->not->toUse('Misaf\VendraTenant');

arch('contracts do not depend on implementation layers')
    ->expect('Misaf\VendraSupport\Contracts')
    ->not->toUse([
        'Misaf\VendraSupport\Authorization',
        'Misaf\VendraSupport\Capabilities',
        'Misaf\VendraSupport\Filament',
        'Misaf\VendraSupport\Tenancy',
    ]);

arch('tenancy is isolated from sibling implementation layers')
    ->expect('Misaf\VendraSupport\Tenancy')
    ->not->toUse([
        'Misaf\VendraSupport\Authorization',
        'Misaf\VendraSupport\Capabilities',
        'Misaf\VendraSupport\Filament',
    ]);

arch('capabilities are isolated from sibling implementation layers')
    ->expect('Misaf\VendraSupport\Capabilities')
    ->not->toUse([
        'Misaf\VendraSupport\Authorization',
        'Misaf\VendraSupport\Filament',
        'Misaf\VendraSupport\Tenancy',
    ]);

arch('authorization is isolated from sibling implementation layers')
    ->expect('Misaf\VendraSupport\Authorization')
    ->not->toUse([
        'Misaf\VendraSupport\Capabilities',
        'Misaf\VendraSupport\Filament',
        'Misaf\VendraSupport\Tenancy',
    ]);

arch('tenant provisioning dispatches after database commits')
    ->expect(TenantProvisioned::class)
    ->toImplement(ShouldDispatchAfterCommit::class);
