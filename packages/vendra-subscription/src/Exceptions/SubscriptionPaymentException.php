<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Exceptions;

use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use RuntimeException;

final class SubscriptionPaymentException extends RuntimeException
{
    public static function collectionFailed(Subscription $subscription): self
    {
        return new self(sprintf(
            'Payment collection failed for subscription [%s].',
            $subscription->id,
        ));
    }

    public static function missingCurrency(Plan $plan): self
    {
        return new self(sprintf(
            'Paid plan [%s] must define a currency.',
            $plan->id,
        ));
    }
}
