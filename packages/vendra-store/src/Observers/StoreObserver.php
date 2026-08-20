<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Observers;

use Misaf\VendraStore\Models\Store;

/**
 * Keeps a store's domains in step with the store itself.
 *
 * Every hook runs inside `$store->execute()` so the domain query resolves
 * against this store rather than whatever tenant happens to be active on the
 * request. Synchronous because `deleting` has to see the domains while they are
 * still there, and because `isForceDeleting()` is request state that would not
 * survive a trip through the queue.
 */
final class StoreObserver
{
    public function deleting(Store $store): void
    {
        $store->execute(function () use ($store): void {
            if ($store->isForceDeleting()) {
                $store->storeDomains()->withTrashed()->forceDelete();

                return;
            }

            $store->storeDomains()->where('active', true)->delete();
        });
    }

    public function restored(Store $store): void
    {
        $store->execute(fn() => $store->storeDomains()->onlyTrashed()->where('active', true)->restore());
    }
}
