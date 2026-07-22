<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use Misaf\VendraSubscription\Exceptions\SubscriptionPaymentException;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;

final class ChargeSubscriptionAction
{
    public function __construct(private readonly SubscriptionCharger $subscriptionCharger) {}

    /**
     * Collect payment for a subscription period from the reseller owner, when a
     * payment provider is available and the plan is not free. No-op (returns
     * false) for free plans, when no charger is bound, or when the reseller has
     * no owner to bill.
     */
    public function execute(Reseller $reseller, Subscription $subscription): bool
    {
        if ($subscription->price <= 0 || null === $subscription->currency_code) {
            return false;
        }

        // Payment is deferred until the trial ends.
        if ($subscription->isOnTrial()) {
            return false;
        }

        if ( ! $this->subscriptionCharger->available()) {
            return false;
        }

        $owner = $reseller->ownerUser()->first();

        if (null === $owner) {
            return false;
        }

        $collected = $this->subscriptionCharger->charge(
            $owner,
            $subscription->price,
            $subscription->currency_code,
            'subscription:' . $subscription->id,
        );

        if ( ! $collected) {
            throw SubscriptionPaymentException::collectionFailed($subscription);
        }

        return true;
    }
}
