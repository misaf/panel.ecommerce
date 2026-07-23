<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Misaf\VendraSubscription\Contracts\SubscriptionSubscriber;
use Misaf\VendraSubscription\Enums\SubscriptionPaymentStatus;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Events\SubscriptionActivated;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Exceptions\SubscriptionPaymentException;
use Misaf\VendraSubscription\Jobs\ProcessSubscriptionPayment;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Models\SubscriptionPayment;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;

final class SubscribeAction
{
    public function __construct(private readonly SubscriptionCharger $subscriptionCharger) {}

    /**
     * Create a subscription period for any subscriber. Paid periods remain
     * pending without replacing current access until their durable payment
     * succeeds; immediately active periods raise SubscriptionActivated so
     * consumers can react (e.g. notifying the owner).
     *
     * @param  Model&SubscriptionSubscriber  $subscriber
     *
     * @throws SubscriptionLimitException when the plan cannot hold the subscriber's current properties
     */
    public function execute(SubscriptionSubscriber $subscriber, Plan $plan, ?Carbon $startsAt = null): Subscription
    {
        if ($plan->price > 0 && null === $plan->currency_code) {
            throw SubscriptionPaymentException::missingCurrency($plan);
        }

        $startsAt ??= Carbon::now();

        $result = DB::transaction(function () use ($subscriber, $plan, $startsAt): array {
            $lockedSubscriber = $subscriber->lockForSubscription();

            $currentProperties = $lockedSubscriber->subscribedPropertyCount();

            if ($currentProperties > $plan->max_units) {
                throw SubscriptionLimitException::planBelowUsage($lockedSubscriber, $plan->max_units, $currentProperties);
            }

            $openPayments = $lockedSubscriber->lockOpenSubscriptionPayments();

            if ($openPayments->contains(fn(SubscriptionPayment $payment): bool => SubscriptionPaymentStatus::Pending !== $payment->status)) {
                throw SubscriptionPaymentException::paymentInProgress();
            }

            if ($openPayments->isNotEmpty()) {
                SubscriptionPayment::query()
                    ->whereKey($openPayments->modelKeys())
                    ->update(['status' => SubscriptionPaymentStatus::Cancelled->value]);
                $lockedSubscriber->cancelPendingPaymentSubscriptions(
                    $openPayments->map(fn(SubscriptionPayment $payment): int => $payment->subscription_id)->all(),
                );
            }

            // A trial only applies to the subscriber's very first subscription.
            $trialEndsAt = $plan->hasTrial() && ! $lockedSubscriber->hasSubscriptions()
                ? $startsAt->copy()->addDays($plan->trial_days)
                : null;
            $requiresCollection = $plan->price > 0;
            $requiresImmediatePayment = $requiresCollection && null === $trialEndsAt;

            if ( ! $requiresImmediatePayment) {
                $lockedSubscriber->cancelActiveSubscriptions();
            }

            $subscription = $lockedSubscriber->createSubscription([
                'plan_id'       => $plan->getKey(),
                'status'        => $requiresImmediatePayment ? SubscriptionStatus::PendingPayment : SubscriptionStatus::Active,
                'price'         => $plan->price,
                'currency_code' => $plan->currency_code,
                'trial_ends_at' => $trialEndsAt,
                'starts_at'     => $startsAt,
                'ends_at'       => $plan->resolveEndDate($startsAt),
            ]);

            if ( ! $requiresImmediatePayment) {
                $lockedSubscriber->reactivateSuspendedProperties();

                if ( ! $requiresCollection) {
                    return ['subscription' => $subscription, 'payment' => null];
                }
            }

            if ( ! $this->subscriptionCharger->available()) {
                throw SubscriptionPaymentException::providerUnavailable();
            }

            $payer = $lockedSubscriber->subscriptionPayer();

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

        if (SubscriptionStatus::Active === $subscription->status) {
            SubscriptionActivated::dispatch($subscription);
        }

        return $subscription;
    }
}
