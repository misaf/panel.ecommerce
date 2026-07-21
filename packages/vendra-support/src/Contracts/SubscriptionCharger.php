<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Optional capability: collect a subscription payment from a payer.
 *
 * A payment provider (e.g. backed by vendra-transaction) binds a real
 * implementation; without one the null default reports it is unavailable so
 * consumers skip collection.
 */
interface SubscriptionCharger
{
    public function available(): bool;

    /**
     * Charge the payer the given amount (in minor units) in the currency.
     *
     * @return bool true when the charge was collected
     */
    public function charge(Model $payer, int $amount, string $currencyCode, string $reference): bool;
}
