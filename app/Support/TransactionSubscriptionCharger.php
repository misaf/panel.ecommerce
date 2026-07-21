<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraTransaction\Enums\TransactionTypeEnum;
use Misaf\VendraTransaction\Facades\TransactionService;
use Misaf\VendraTransaction\Services\TransactionService as TransactionServiceClass;

/**
 * Collects subscription payments through vendra-transaction by posting an
 * internal withdrawal against the payer's wallet.
 */
final class TransactionSubscriptionCharger implements SubscriptionCharger
{
    public function available(): bool
    {
        return TransactionService::hasActiveTransactionGateway(TransactionServiceClass::INTERNAL_GATEWAY_SLUG);
    }

    public function charge(Model $payer, int $amount, string $currencyCode, string $reference): bool
    {
        $wallet = TransactionService::walletFor($payer, $currencyCode);

        TransactionService::createTransaction(
            TransactionServiceClass::INTERNAL_GATEWAY_SLUG,
            $wallet,
            TransactionTypeEnum::Withdrawal,
            $amount,
            ['reference' => $reference],
        );

        return true;
    }
}
