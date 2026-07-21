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
        $subscription = $account->activeSubscription();

        if (null === $subscription) {
            return false;
        }

        return $account->tenants()->count() < $subscription->plan->max_websites;
    }

    /**
     * The number of additional websites the account may still create.
     */
    public function remainingWebsites(Account $account): int
    {
        $subscription = $account->activeSubscription();

        if (null === $subscription) {
            return 0;
        }

        return max(0, $subscription->plan->max_websites - $account->tenants()->count());
    }

    /**
     * @throws SubscriptionLimitException when the account may not create a website
     */
    public function assertCanCreateWebsite(Account $account): void
    {
        $subscription = $account->activeSubscription();

        if (null === $subscription) {
            throw SubscriptionLimitException::noActiveSubscription($account);
        }

        if ($account->tenants()->count() >= $subscription->plan->max_websites) {
            throw SubscriptionLimitException::websiteQuotaReached($account, $subscription->plan->max_websites);
        }
    }
}
