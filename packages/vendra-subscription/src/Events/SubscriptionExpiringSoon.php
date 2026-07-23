<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Misaf\VendraSubscription\Models\Subscription;

/**
 * An active subscription is approaching its expiry and has not yet been
 * reminded. Consumers react by reminding the subscriber's owner. The engine
 * marks the subscription reminded regardless of whether a consumer acts.
 */
final class SubscriptionExpiringSoon
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly Subscription $subscription) {}
}
