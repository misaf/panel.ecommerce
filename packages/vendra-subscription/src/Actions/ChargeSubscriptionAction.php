<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;

final class ChargeSubscriptionAction
{
    public function __construct(private readonly SubscriptionCharger $subscriptionCharger) {}

    /**
     * Collect payment for a subscription period from the account owner, when a
     * payment provider is available and the plan is not free. No-op (returns
     * false) for free plans, when no charger is bound, or when the account has
     * no owner to bill.
     */
    public function execute(Account $account, Subscription $subscription): bool
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

        $owner = $account->ownerUser()->first();

        if (null === $owner) {
            return false;
        }

        return $this->subscriptionCharger->charge(
            $owner,
            $subscription->price,
            $subscription->currency_code,
            'subscription:' . $subscription->getKey(),
        );
    }
}
