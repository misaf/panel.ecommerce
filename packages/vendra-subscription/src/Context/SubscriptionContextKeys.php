<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Context;

/**
 * Subscription-owned observability context keys passed through
 * {@see \Misaf\VendraSupport\Context\RequestJobContext::$metadata}.
 */
final class SubscriptionContextKeys
{
    public const string SUBSCRIPTION_ID = 'subscription_id';

    public const string PAYMENT_ID = 'payment_id';
}
