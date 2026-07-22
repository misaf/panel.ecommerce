<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\SubscriptionLimitException;
use App\Models\Reseller;
use App\Notifications\SubscriptionActivatedNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Exceptions\SubscriptionPaymentException;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;

final class SubscribeResellerAction
{
    public function __construct(private readonly ChargeSubscriptionAction $chargeSubscriptionAction) {}

    /**
     * Subscribe the reseller to a plan, cancelling any currently active
     * subscription so only one is ever active at a time. Used for the initial
     * subscription as well as plan changes and renewals.
     *
     * @throws SubscriptionLimitException when the plan cannot hold the reseller's current properties
     */
    public function execute(Reseller $reseller, Plan $plan, ?Carbon $startsAt = null): Subscription
    {
        if ($plan->price > 0 && null === $plan->currency_code) {
            throw SubscriptionPaymentException::missingCurrency($plan);
        }

        $startsAt ??= Carbon::now();

        $subscription = DB::transaction(function () use ($reseller, $plan, $startsAt): Subscription {
            $lockedReseller = Reseller::query()
                ->whereKey($reseller->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $currentProperties = $lockedReseller->tenants()->count();

            if ($currentProperties > $plan->max_units) {
                throw SubscriptionLimitException::planBelowUsage($lockedReseller, $plan->max_units, $currentProperties);
            }

            // A trial only applies to the reseller's very first subscription.
            $trialEndsAt = $plan->hasTrial() && ! $lockedReseller->subscriptions()->exists()
                ? $startsAt->copy()->addDays($plan->trial_days)
                : null;

            $lockedReseller->subscriptions()
                ->where('status', SubscriptionStatus::Active->value)
                ->update(['status' => SubscriptionStatus::Cancelled->value]);

            $subscription = $lockedReseller->subscriptions()->create([
                'plan_id'       => $plan->getKey(),
                'status'        => SubscriptionStatus::Active,
                'price'         => $plan->price,
                'currency_code' => $plan->currency_code,
                'trial_ends_at' => $trialEndsAt,
                'starts_at'     => $startsAt,
                'ends_at'       => $plan->resolveEndDate($startsAt),
            ]);

            // Restore any properties suspended while the reseller had no active plan.
            $lockedReseller->tenants()->where('status', false)->update(['status' => true]);

            $this->chargeSubscriptionAction->execute($lockedReseller, $subscription);

            return $subscription;
        }, attempts: 5);

        if ($reseller->hasOwnerContact()) {
            $reseller->notify(new SubscriptionActivatedNotification($plan));
        }

        return $subscription;
    }
}
