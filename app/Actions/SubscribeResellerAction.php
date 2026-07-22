<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\SubscriptionLimitException;
use App\Jobs\ProcessSubscriptionPayment;
use App\Models\Reseller;
use App\Notifications\SubscriptionActivatedNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Misaf\VendraSubscription\Enums\SubscriptionPaymentStatus;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Exceptions\SubscriptionPaymentException;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Models\SubscriptionPayment;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;

final class SubscribeResellerAction
{
    public function __construct(private readonly SubscriptionCharger $subscriptionCharger) {}

    /**
     * Create a subscription period. Paid periods remain pending without
     * replacing current access until their durable payment succeeds.
     *
     * @throws SubscriptionLimitException when the plan cannot hold the reseller's current properties
     */
    public function execute(Reseller $reseller, Plan $plan, ?Carbon $startsAt = null): Subscription
    {
        if ($plan->price > 0 && null === $plan->currency_code) {
            throw SubscriptionPaymentException::missingCurrency($plan);
        }

        $startsAt ??= Carbon::now();

        $result = DB::transaction(function () use ($reseller, $plan, $startsAt): array {
            $lockedReseller = Reseller::query()
                ->whereKey($reseller->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $currentProperties = $lockedReseller->tenants()->count();

            if ($currentProperties > $plan->max_units) {
                throw SubscriptionLimitException::planBelowUsage($lockedReseller, $plan->max_units, $currentProperties);
            }

            $openPayments = SubscriptionPayment::query()
                ->whereIn('subscription_id', $lockedReseller->subscriptions()->select('id'))
                ->whereIn('status', [
                    SubscriptionPaymentStatus::Pending,
                    SubscriptionPaymentStatus::Processing,
                    SubscriptionPaymentStatus::RequiresAction,
                    SubscriptionPaymentStatus::NeedsReconciliation,
                ])
                ->lockForUpdate()
                ->get();

            if ($openPayments->contains(fn(SubscriptionPayment $payment): bool => SubscriptionPaymentStatus::Pending !== $payment->status)) {
                throw SubscriptionPaymentException::paymentInProgress();
            }

            if ($openPayments->isNotEmpty()) {
                SubscriptionPayment::query()
                    ->whereKey($openPayments->modelKeys())
                    ->update(['status' => SubscriptionPaymentStatus::Cancelled->value]);
                $lockedReseller->subscriptions()
                    ->whereKey($openPayments->pluck('subscription_id'))
                    ->where('status', SubscriptionStatus::PendingPayment)
                    ->update(['status' => SubscriptionStatus::Cancelled->value]);
            }

            // A trial only applies to the reseller's very first subscription.
            $trialEndsAt = $plan->hasTrial() && ! $lockedReseller->subscriptions()->exists()
                ? $startsAt->copy()->addDays($plan->trial_days)
                : null;
            $requiresCollection = $plan->price > 0;
            $requiresImmediatePayment = $requiresCollection && null === $trialEndsAt;

            if ( ! $requiresImmediatePayment) {
                $lockedReseller->subscriptions()
                    ->where('status', SubscriptionStatus::Active->value)
                    ->update(['status' => SubscriptionStatus::Cancelled->value]);
            }

            $subscription = $lockedReseller->subscriptions()->create([
                'plan_id'       => $plan->getKey(),
                'status'        => $requiresImmediatePayment ? SubscriptionStatus::PendingPayment : SubscriptionStatus::Active,
                'price'         => $plan->price,
                'currency_code' => $plan->currency_code,
                'trial_ends_at' => $trialEndsAt,
                'starts_at'     => $startsAt,
                'ends_at'       => $plan->resolveEndDate($startsAt),
            ]);

            if ( ! $requiresImmediatePayment) {
                $lockedReseller->tenants()->where('status', false)->update(['status' => true]);

                if ( ! $requiresCollection) {
                    return ['subscription' => $subscription, 'payment' => null];
                }
            }

            if ( ! $this->subscriptionCharger->available()) {
                throw SubscriptionPaymentException::providerUnavailable();
            }

            $payer = $lockedReseller->ownerUser()->first();

            if (null === $payer) {
                throw SubscriptionPaymentException::missingPayer($subscription);
            }

            $payment = $subscription->payments()->make([
                'provider'        => $this->subscriptionCharger->provider(),
                'idempotency_key' => (string) Str::uuid(),
                'amount'          => $subscription->price,
                'currency_code'   => $subscription->currency_code,
                'next_retry_at'   => $trialEndsAt,
            ]);
            $payment->payer()->associate($payer);
            $payment->save();

            return ['subscription' => $subscription, 'payment' => $payment];
        }, attempts: 5);

        $subscription = $result['subscription'];
        $payment = $result['payment'];

        if ($payment instanceof SubscriptionPayment && null === $payment->next_retry_at) {
            ProcessSubscriptionPayment::dispatch($payment->id)->afterCommit();
        }

        if (SubscriptionStatus::Active === $subscription->status && $reseller->hasOwnerContact()) {
            $reseller->notify(new SubscriptionActivatedNotification($plan));
        }

        return $subscription;
    }
}
