<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Database\Eloquent\Collection;
use Misaf\VendraSubscription\Contracts\SubscriptionSubscriber;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Events\SubscriptionExpiringSoon;
use Misaf\VendraSubscription\Events\SubscriptionGraceExpired;
use Misaf\VendraSubscription\Models\Subscription;

/**
 * The subscriber-agnostic enforcement engine: it expires lapsed subscriptions,
 * marks soon-to-expire ones reminded, and detects subscribers past their grace
 * window. Host-specific reactions (notifying owners, suspending properties)
 * happen in consumers of SubscriptionExpiringSoon and SubscriptionGraceExpired,
 * so this action never touches notifications or properties directly.
 */
final class EnforceSubscriptionsAction
{
    /**
     * Number of days before expiry that an owner is reminded to renew.
     */
    private const int EXPIRY_REMINDER_DAYS = 7;

    /**
     * @return array{expired: int, reminded: int, grace_expired: int}
     */
    public function execute(): array
    {
        return [
            'expired'       => $this->expireLapsedSubscriptions(),
            'reminded'      => $this->remindExpiringSubscriptions(),
            'grace_expired' => $this->flagPastGraceSubscribers(),
        ];
    }

    private function expireLapsedSubscriptions(): int
    {
        return Subscription::query()
            ->lapsed()
            ->update(['status' => SubscriptionStatus::Expired->value]);
    }

    private function remindExpiringSubscriptions(): int
    {
        $reminded = 0;

        Subscription::query()
            ->expiringWithin(self::EXPIRY_REMINDER_DAYS)
            ->chunkById(100, function (Collection $subscriptions) use (&$reminded): void {
                /** @var Collection<int, Subscription> $subscriptions */
                foreach ($subscriptions as $subscription) {
                    SubscriptionExpiringSoon::dispatch($subscription);

                    $subscription->forceFill(['expiry_reminder_sent_at' => now()])->save();
                    $reminded++;
                }
            });

        return $reminded;
    }

    private function flagPastGraceSubscribers(): int
    {
        $flagged = 0;

        /** @var array<string, true> $processed */
        $processed = [];

        Subscription::query()
            ->whereNotIn('status', [
                SubscriptionStatus::Active->value,
                SubscriptionStatus::PendingPayment->value,
            ])
            ->with('subscriber')
            ->chunkById(100, function (Collection $subscriptions) use (&$flagged, &$processed): void {
                /** @var Collection<int, Subscription> $subscriptions */
                foreach ($subscriptions as $subscription) {
                    $subscriber = $subscription->subscriber;

                    if ( ! $subscriber instanceof SubscriptionSubscriber) {
                        continue;
                    }

                    $key = $subscription->subscriber_type . ':' . $subscription->subscriber_id;

                    if (isset($processed[$key])) {
                        continue;
                    }

                    $processed[$key] = true;

                    if (0 === $subscriber->activeSubscribedPropertyCount() || null !== $subscriber->activeSubscription()) {
                        continue;
                    }

                    $latest = $subscriber->latestSubscription();
                    $suspendAt = $latest?->suspendAt();

                    if (null === $latest || null === $suspendAt || $suspendAt->isFuture()) {
                        continue;
                    }

                    SubscriptionGraceExpired::dispatch($latest);
                    $flagged++;
                }
            });

        return $flagged;
    }
}
