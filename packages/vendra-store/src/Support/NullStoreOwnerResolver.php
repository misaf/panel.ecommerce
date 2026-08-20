<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Support;

use Misaf\VendraStore\Contracts\StoreOwnerResolver;
use Misaf\VendraSubscription\Contracts\SubscriptionSubscriber;

/**
 * The default owner resolver: with no reseller domain installed, a store cannot
 * have a billing owner, so nothing resolves. Callers treat an unresolvable owner
 * as "not paying", which fails closed rather than granting free access.
 */
final class NullStoreOwnerResolver implements StoreOwnerResolver
{
    public function find(int|string $ownerId): ?SubscriptionSubscriber
    {
        return null;
    }
}
