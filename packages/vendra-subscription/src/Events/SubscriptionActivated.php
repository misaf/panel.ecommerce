<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Misaf\VendraSubscription\Models\Subscription;

/**
 * A subscription became active (its properties are already reactivated within
 * the activating transaction). Consumers react with host-specific side effects
 * such as notifying the subscriber's owner.
 */
final class SubscriptionActivated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly Subscription $subscription) {}
}
