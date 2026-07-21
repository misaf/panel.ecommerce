<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Exceptions;

use Misaf\VendraSubscription\Models\Account;
use RuntimeException;

final class SubscriptionLimitException extends RuntimeException
{
    public static function accountDisabled(Account $account): self
    {
        return new self(sprintf(
            'Account [%s] is disabled.',
            $account->id,
        ));
    }

    public static function noActiveSubscription(Account $account): self
    {
        return new self(sprintf(
            'Account [%s] has no active subscription.',
            $account->id,
        ));
    }

    public static function websiteQuotaReached(Account $account, int $maxWebsites): self
    {
        return new self(sprintf(
            'Account [%s] has reached its website limit of [%d].',
            $account->id,
            $maxWebsites,
        ));
    }

    public static function planBelowUsage(Account $account, int $maxWebsites, int $currentWebsites): self
    {
        return new self(sprintf(
            'Account [%s] has %d website(s), which exceeds the [%d] allowed by the selected plan.',
            $account->id,
            $currentWebsites,
            $maxWebsites,
        ));
    }
}
