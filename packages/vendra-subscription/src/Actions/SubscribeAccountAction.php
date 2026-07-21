<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Notifications\SubscriptionActivatedNotification;

final class SubscribeAccountAction
{
    public function __construct(private readonly ChargeSubscriptionAction $chargeSubscriptionAction) {}

    /**
     * Subscribe the account to a plan, cancelling any currently active
     * subscription so only one is ever active at a time. Used for the initial
     * subscription as well as plan changes and renewals.
     *
     * @throws SubscriptionLimitException when the plan cannot hold the account's current websites
     */
    public function execute(Account $account, Plan $plan, ?Carbon $startsAt = null): Subscription
    {
        $currentWebsites = $account->tenants()->count();

        if ($currentWebsites > $plan->max_websites) {
            throw SubscriptionLimitException::planBelowUsage($account, $plan->max_websites, $currentWebsites);
        }

        $startsAt ??= Carbon::now();

        // A trial only applies to the account's very first subscription.
        $trialEndsAt = $plan->hasTrial() && ! $account->subscriptions()->exists()
            ? $startsAt->copy()->addDays($plan->trial_days)
            : null;

        $subscription = DB::transaction(function () use ($account, $plan, $startsAt, $trialEndsAt): Subscription {
            $account->subscriptions()
                ->where('status', SubscriptionStatus::Active->value)
                ->update(['status' => SubscriptionStatus::Cancelled->value]);

            $subscription = $account->subscriptions()->create([
                'plan_id'       => $plan->getKey(),
                'status'        => SubscriptionStatus::Active,
                'price'         => $plan->price,
                'currency_code' => $plan->currency_code,
                'trial_ends_at' => $trialEndsAt,
                'starts_at'     => $startsAt,
                'ends_at'       => $plan->resolveEndDate($startsAt),
            ]);

            // Restore any websites suspended while the account had no active plan.
            $account->tenants()->where('status', false)->update(['status' => true]);

            return $subscription;
        });

        $this->chargeSubscriptionAction->execute($account, $subscription);

        if ($account->hasOwnerContact()) {
            $account->notify(new SubscriptionActivatedNotification($plan));
        }

        return $subscription;
    }
}
