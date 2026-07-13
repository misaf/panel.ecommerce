<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Events;

use Illuminate\Database\Eloquent\Model;

/**
 * Fired after a tenant has been provisioned. Modules subscribe to run their
 * own tenant-scoped seeders (see TenantSeeders) or other provisioning side
 * effects, without the provisioning module needing to know they exist.
 */
final class TenantProvisioned
{
    public function __construct(
        public readonly Model $tenant,
        public readonly bool $shouldSeed = false,
    ) {}
}
