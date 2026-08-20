<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Actions;

use Illuminate\Support\Facades\Validator;
use Misaf\VendraStore\Models\Store;
use Misaf\VendraStore\Models\StoreDomain;
use UnexpectedValueException;

final class ReplaceStoreDomainAction
{
    /**
     * Replace a store's active domain, retaining the previous one as history.
     *
     * The current active domain (active = true) is demoted to a replaced
     * history record (active = false) and soft-deleted, so it stops resolving
     * but stays visible behind the trashed filter. A fresh active domain is
     * then created. Runs in the store's own tenant context so the domain
     * records are scoped to this store regardless of the currently active one.
     */
    public function execute(Store $store, string $domain): StoreDomain
    {
        $domain = StoreDomain::normalizeDomain($domain);
        Validator::make(
            ['domain' => $domain],
            ['domain' => StoreDomain::activeDomainRules()],
        )->validate();

        $storeDomain = $store->execute(function () use ($store, $domain): StoreDomain {
            $store->storeDomains()
                ->where('active', true)
                ->get()
                ->each(function (StoreDomain $current): void {
                    $current->forceFill(['active' => false])->save();
                    $current->delete();
                });

            return $store->storeDomains()->create([
                'name'   => $domain,
                'slug'   => $domain,
                'active' => true,
            ]);
        });

        if ( ! $storeDomain instanceof StoreDomain) {
            throw new UnexpectedValueException('Replacing a store domain did not return a domain model.');
        }

        return $storeDomain;
    }
}
