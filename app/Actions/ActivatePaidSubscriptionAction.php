<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use App\Notifications\SubscriptionActivatedNotification;
use Illuminate\Support\Facades\DB;
use Misaf\VendraSubscription\Enums\SubscriptionPaymentStatus;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Models\SubscriptionPayment;

final class ActivatePaidSubscriptionAction
{
    public function execute(SubscriptionPayment $payment): void
    {
        $paymentId = $payment->id;
        $activated = DB::transaction(function () use ($paymentId): ?Subscription {
            $payment = SubscriptionPayment::query()->whereKey($paymentId)->firstOrFail();
            $subscription = $payment->subscription()->firstOrFail();
            $subscriber = $subscription->subscriber()->firstOrFail();

            if ( ! $subscriber instanceof Reseller) {
                return null;
            }

            $lockedReseller = Reseller::query()
                ->whereKey($subscriber->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $lockedPayment = SubscriptionPayment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();
            $lockedSubscription = Subscription::query()
                ->whereKey($subscription->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (SubscriptionPaymentStatus::Paid !== $lockedPayment->status
                || SubscriptionStatus::PendingPayment !== $lockedSubscription->status) {
                return null;
            }

            $lockedReseller->subscriptions()
                ->whereKeyNot($lockedSubscription->getKey())
                ->where('status', SubscriptionStatus::Active->value)
                ->update(['status' => SubscriptionStatus::Cancelled->value]);
            $lockedSubscription->update(['status' => SubscriptionStatus::Active]);
            $lockedReseller->tenants()->where('status', false)->update(['status' => true]);

            return $lockedSubscription;
        }, attempts: 5);

        if ($activated instanceof Subscription) {
            $subscriber = $activated->subscriber()->first();
            $plan = $activated->plan()->firstOrFail();

            if ($subscriber instanceof Reseller && $subscriber->hasOwnerContact()) {
                $subscriber->notify(new SubscriptionActivatedNotification($plan));
            }
        }
    }
}
