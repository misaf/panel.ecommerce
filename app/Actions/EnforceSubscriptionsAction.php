<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use App\Notifications\PropertiesSuspendedNotification;
use App\Notifications\SubscriptionExpiringNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Subscription;

final class EnforceSubscriptionsAction
{
    /**
     * Number of days before expiry that an owner is reminded to renew.
     */
    private const int EXPIRY_REMINDER_DAYS = 7;

    /**
     * Expire lapsed subscriptions, remind owners of soon-to-expire ones, and
     * suspend the properties of resellers whose subscription ended more than the
     * plan's grace window ago.
     *
     * @return array{expired: int, reminded: int, suspended_properties: int, suspended_resellers: int}
     */
    public function execute(): array
    {
        $expired = $this->expireLapsedSubscriptions();

        $reminded = $this->remindExpiringSubscriptions();

        [$suspendedProperties, $suspendedResellers] = $this->suspendPastGraceProperties();

        return [
            'expired'               => $expired,
            'reminded'              => $reminded,
            'suspended_properties'  => $suspendedProperties,
            'suspended_resellers'   => $suspendedResellers,
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
        $resellerType = (new Reseller())->getMorphClass();

        Subscription::query()
            ->expiringWithin(self::EXPIRY_REMINDER_DAYS)
            ->chunkById(100, function (Collection $subscriptions) use (&$reminded, $resellerType): void {
                /** @var Collection<int, Subscription> $subscriptions */
                $resellers = Reseller::query()
                    ->whereIn('id', $subscriptions->where('subscriber_type', $resellerType)->pluck('subscriber_id')->all())
                    ->get()
                    ->keyBy('id');

                foreach ($subscriptions as $subscription) {
                    $reseller = $subscription->subscriber_type === $resellerType
                        ? $resellers->get($subscription->subscriber_id)
                        : null;

                    if (null !== $reseller && $reseller->hasOwnerContact()) {
                        $reseller->notify(new SubscriptionExpiringNotification($subscription));
                    }

                    $subscription->forceFill(['expiry_reminder_sent_at' => now()])->save();
                    $reminded++;
                }
            });

        return $reminded;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function suspendPastGraceProperties(): array
    {
        $suspendedProperties = 0;
        $suspendedResellers = 0;

        Reseller::query()
            ->whereHas('tenants', fn(Builder $query): Builder => $query->where('status', true))
            ->chunkById(100, function (Collection $resellers) use (&$suspendedProperties, &$suspendedResellers): void {
                /** @var Collection<int, Reseller> $resellers */
                foreach ($resellers as $reseller) {
                    if (null !== $reseller->activeSubscription()) {
                        continue;
                    }

                    $latest = $reseller->subscriptions()->latest('starts_at')->first();
                    $suspendAt = $latest?->suspendAt();

                    if (null === $suspendAt || $suspendAt->isFuture()) {
                        continue;
                    }

                    $count = $reseller->tenants()->where('status', true)->update(['status' => false]);

                    if ($count > 0) {
                        $suspendedProperties += $count;
                        $suspendedResellers++;

                        if ($reseller->hasOwnerContact()) {
                            $reseller->notify(new PropertiesSuspendedNotification($count));
                        }
                    }
                }
            });

        return [$suspendedProperties, $suspendedResellers];
    }
}
