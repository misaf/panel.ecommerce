<?php

declare(strict_types=1);

namespace App\Support;

use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraSupport\Data\SubscriptionCharge;
use Misaf\VendraSupport\Data\SubscriptionChargeResult;
use Misaf\VendraSupport\Enums\SubscriptionChargeStatus;
use Misaf\VendraTransaction\Enums\TransactionTypeEnum;
use Misaf\VendraTransaction\Exceptions\InsufficientBalanceException;
use Misaf\VendraTransaction\Facades\TransactionService;
use Misaf\VendraTransaction\Models\Transaction;
use Misaf\VendraTransaction\Services\TransactionService as TransactionServiceClass;
use Misaf\VendraTransaction\States\Approved;
use Misaf\VendraTransaction\States\Declined;
use Misaf\VendraTransaction\States\Failed;

/**
 * Collects subscription payments through vendra-transaction by posting an
 * internal withdrawal against the payer's wallet.
 */
final class TransactionSubscriptionCharger implements SubscriptionCharger
{
    public function provider(): string
    {
        return TransactionServiceClass::INTERNAL_GATEWAY_SLUG;
    }

    public function available(): bool
    {
        return TransactionService::hasActiveTransactionGateway(TransactionServiceClass::INTERNAL_GATEWAY_SLUG);
    }

    public function charge(SubscriptionCharge $charge): SubscriptionChargeResult
    {
        $wallet = TransactionService::walletFor($charge->payer, $charge->currencyCode);

        $transaction = TransactionService::createTransaction(
            TransactionServiceClass::INTERNAL_GATEWAY_SLUG,
            $wallet,
            TransactionTypeEnum::Withdrawal,
            $charge->amount,
            ['reference' => $charge->reference],
            idempotencyKey: $charge->reference,
        );

        if ($transaction->status->canTransitionTo(Approved::class)) {
            try {
                $transaction->approve();
            } catch (InsufficientBalanceException $exception) {
                if ($transaction->status->canTransitionTo(Declined::class)) {
                    $transaction->decline();
                }

                return new SubscriptionChargeResult(
                    SubscriptionChargeStatus::Failed,
                    providerReference: (string) $transaction->id,
                    errorCode: 'insufficient_balance',
                    errorMessage: $exception->getMessage(),
                );
            }
        }

        return $this->resultFor($transaction);
    }

    public function retrieve(SubscriptionCharge $charge): SubscriptionChargeResult
    {
        return $this->charge($charge);
    }

    private function resultFor(Transaction $transaction): SubscriptionChargeResult
    {
        $status = match (true) {
            $transaction->status instanceof Approved => SubscriptionChargeStatus::Paid,
            $transaction->status instanceof Declined,
            $transaction->status instanceof Failed   => SubscriptionChargeStatus::Failed,
            default                                  => SubscriptionChargeStatus::Processing,
        };

        return new SubscriptionChargeResult(
            $status,
            providerReference: (string) $transaction->id,
        );
    }
}
