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

    public static function missingPayer(Subscription $subscription): self
    {
        return new self(sprintf(
            'Subscription [%s] has no payer available for collection.',
            $subscription->id,
        ));
    }

    public static function providerUnavailable(): self
    {
        return new self('No subscription payment provider is available.');
    }

    public static function paymentInProgress(): self
    {
        return new self('A subscription payment is already in progress.');
    }
}
