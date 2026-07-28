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

            $payment->recordProviderResult(
                $status,
                $result->providerReference,
                $result->errorCode,
                $result->errorMessage,
            );

            $failed = false;

            if (SubscriptionPaymentStatus::Failed === $status) {
                $failed = true;
                $subscription = $payment->subscription()->firstOrFail();

                if (SubscriptionStatus::PendingPayment === $subscription->status) {
                    $subscription->cancel();
                } elseif (SubscriptionStatus::Active === $subscription->status) {
                    $subscription->markPastDue();
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
