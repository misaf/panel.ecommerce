<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

use Misaf\VendraSupport\Data\SubscriptionCharge;
use Misaf\VendraSupport\Data\SubscriptionChargeResult;

/**
 * Optional capability: collect a subscription payment from a payer.
 *
 * A payment provider (e.g. backed by vendra-transaction) binds a real
 * implementation; without one the null default reports it is unavailable so
 * consumers skip collection.
 */
interface SubscriptionCharger
{
    /**
     * Stable programmatic name persisted with each payment operation. This and
     * available() must be local configuration checks and perform no network I/O.
     */
    public function provider(): string;

    public function available(): bool;

    /**
     * Initiate or resume collection of the given payment operation.
     *
     * Repeating a charge with the same reference and financial payload must
     * resolve to the same provider operation and must never collect funds more
     * than once. Reusing the reference for different details must be rejected.
     */
    public function charge(SubscriptionCharge $charge): SubscriptionChargeResult;

    /**
     * Retrieve the latest outcome of an operation already known to the provider.
     */
    public function retrieve(SubscriptionCharge $charge): SubscriptionChargeResult;
}
