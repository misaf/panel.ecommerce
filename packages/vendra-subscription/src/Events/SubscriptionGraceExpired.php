<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Misaf\VendraSubscription\Models\Subscription;

/**
 * A subscriber's most recent subscription ended more than the plan's grace
 * window ago while it still holds active properties. Consumers react by
 * suspending those properties and notifying the owner. Carries the lapsed
 * subscription; resolve its subscriber to act.
 */
final class SubscriptionGraceExpired
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly Subscription $subscription) {}
}
