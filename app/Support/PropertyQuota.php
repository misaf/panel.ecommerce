<?php

declare(strict_types=1);

namespace App\Support;

use App\Exceptions\SubscriptionLimitException;
use App\Models\Reseller;

final class PropertyQuota
{
    /**
     * Whether the reseller may create another property right now.
     */
    public function canCreateProperty(Reseller $reseller): bool
    {
        if ( ! $reseller->status) {
            return false;
        }

        $subscription = $reseller->activeSubscription();
        $plan = $subscription?->plan;

        if (null === $plan) {
            return false;
        }

        return $reseller->tenants()->count() < $plan->max_units;
    }

    /**
     * The number of additional properties the reseller may still create.
     */
    public function remainingProperties(Reseller $reseller): int
    {
        if ( ! $reseller->status) {
            return 0;
        }

        $subscription = $reseller->activeSubscription();
        $plan = $subscription?->plan;

        if (null === $plan) {
            return 0;
        }

        return max(0, $plan->max_units - $reseller->tenants()->count());
    }

    /**
     * @throws SubscriptionLimitException when the reseller may not create a property
     */
    public function assertCanCreateProperty(Reseller $reseller): void
    {
        if ( ! $reseller->status) {
            throw SubscriptionLimitException::resellerDisabled($reseller);
        }

        $subscription = $reseller->activeSubscription();
        $plan = $subscription?->plan;

        if (null === $plan) {
            throw SubscriptionLimitException::noActiveSubscription($reseller);
        }

        if ($reseller->tenants()->count() >= $plan->max_units) {
            throw SubscriptionLimitException::propertyQuotaReached($reseller, $plan->max_units);
        }
    }
}
