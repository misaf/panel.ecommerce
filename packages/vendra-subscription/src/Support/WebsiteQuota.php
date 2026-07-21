<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Support;

use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Models\Account;

final class WebsiteQuota
{
    /**
     * Whether the account may create another website right now.
     */
    public function canCreateWebsite(Account $account): bool
    {
        if ( ! $account->status) {
            return false;
        }

        $subscription = $account->activeSubscription();
        $plan = $subscription?->plan;

        if (null === $plan) {
            return false;
        }

        return $account->tenants()->count() < $plan->max_websites;
    }

    /**
     * The number of additional websites the account may still create.
     */
    public function remainingWebsites(Account $account): int
    {
        if ( ! $account->status) {
            return 0;
        }

        $subscription = $account->activeSubscription();
        $plan = $subscription?->plan;

        if (null === $plan) {
            return 0;
        }

        return max(0, $plan->max_websites - $account->tenants()->count());
    }

    /**
     * @throws SubscriptionLimitException when the account may not create a website
     */
    public function assertCanCreateWebsite(Account $account): void
    {
        if ( ! $account->status) {
            throw SubscriptionLimitException::accountDisabled($account);
        }

        $subscription = $account->activeSubscription();
        $plan = $subscription?->plan;

        if (null === $plan) {
            throw SubscriptionLimitException::noActiveSubscription($account);
        }

        if ($account->tenants()->count() >= $plan->max_websites) {
            throw SubscriptionLimitException::websiteQuotaReached($account, $plan->max_websites);
        }
    }
}
