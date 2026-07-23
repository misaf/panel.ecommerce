<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Support\Facades\DB;
use LogicException;
use Misaf\VendraSubscription\Enums\SubscriptionPaymentStatus;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Events\SubscriptionPaymentFailed;
use Misaf\VendraSubscription\Models\SubscriptionPayment;
use Misaf\VendraSupport\Data\SubscriptionChargeResult;
use Misaf\VendraSupport\Enums\SubscriptionChargeStatus;

final class ApplySubscriptionPaymentResultAction
{
    public function execute(SubscriptionPayment $payment, SubscriptionChargeResult $result): SubscriptionPayment
    {
        [$payment, $failed] = DB::transaction(function () use ($payment, $result): array {
            $payment = SubscriptionPayment::query()
                ->whereKey($payment->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (null !== $payment->provider_reference
                && null !== $result->providerReference
                && $payment->provider_reference !== $result->providerReference) {
                throw new LogicException("Provider reference changed for subscription payment [{$payment->id}].");
            }

            $status = match ($result->status) {
                SubscriptionChargeStatus::Processing     => SubscriptionPaymentStatus::Processing,
                SubscriptionChargeStatus::RequiresAction => SubscriptionPaymentStatus::RequiresAction,
                SubscriptionChargeStatus::Paid           => SubscriptionPaymentStatus::Paid,
                SubscriptionChargeStatus::Failed         => SubscriptionPaymentStatus::Failed,
            };

            if ($payment->status->isTerminal() && $payment->status !== $status) {
                return [$payment, false];
            }

            $payment->forceFill([
                'status'             => $status,
                'provider_reference' => $payment->provider_reference ?? $result->providerReference,
                'failure_code'       => $result->errorCode,
                'failure_message'    => $result->errorMessage,
                'paid_at'            => SubscriptionPaymentStatus::Paid === $status ? ($payment->paid_at ?? now()) : $payment->paid_at,
                'failed_at'          => SubscriptionPaymentStatus::Failed === $status ? ($payment->failed_at ?? now()) : $payment->failed_at,
                'next_retry_at'      => SubscriptionPaymentStatus::Processing === $status ? now()->addMinutes(5) : null,
            ])->save();

            $failed = false;

            if (SubscriptionPaymentStatus::Failed === $status) {
                $failed = true;
                $subscription = $payment->subscription()->firstOrFail();

                if (SubscriptionStatus::PendingPayment === $subscription->status) {
                    $subscription->update(['status' => SubscriptionStatus::Cancelled]);
                } elseif (SubscriptionStatus::Active === $subscription->status) {
                    $subscription->update(['status' => SubscriptionStatus::PastDue]);
                }
            }

            return [$payment, $failed];
        });

        if ($failed) {
            SubscriptionPaymentFailed::dispatch($payment);
        }

        return $payment;
    }
}
