<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Whether the platform is currently accepting new stores.
 *
 * Platform-wide by definition — a freeze switch for an incident — so it is
 * stored without a tenant and read the same way from every panel. It lives in
 * this package rather than in the console because the reseller panel creates
 * stores too, and `vendra-reseller` sits below the console.
 */
final class StoreCreationSettings extends Settings
{
    /**
     * Whether a new store may be created right now.
     */
    public bool $open;

    public static function group(): string
    {
        return 'store_creation';
    }

    /**
     * Platform settings carry no tenant, so they never use the default
     * store-scoped repository.
     */
    public static function repository(): ?string
    {
        return 'global';
    }
}
