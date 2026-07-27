<?php

declare(strict_types=1);

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Misaf\VendraSupport\Events\TenantProvisioned;

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('support never depends on a concrete tenant provider')
    ->expect('Misaf\VendraSupport')
    ->not->toUse('Misaf\VendraTenant');

arch('tenant provisioning dispatches after database commits')
    ->expect(TenantProvisioned::class)
    ->toImplement(ShouldDispatchAfterCommit::class);
