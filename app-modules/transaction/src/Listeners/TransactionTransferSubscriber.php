<?php

declare(strict_types=1);

namespace Misaf\Transaction\Listeners;

use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\Transaction\Enums\TransactionStatusEnum;
use Misaf\Transaction\Enums\TransactionTypeEnum;
use Misaf\Transaction\Facades\TransactionService;
use Misaf\Transaction\Models\Transaction;
use Misaf\User\Models\User;

final class TransactionTransferSubscriber implements ShouldQueueAfterCommit
{
    use InteractsWithQueue;

    public function transactionUpdated(Transaction $transaction): void
    {
        $isTransferApproved = TransactionService::isTransfer($transaction) && TransactionService::isApproved($transaction);
        if ( ! $isTransferApproved) {
            return;
        }

        $transactionTransfers = $transaction->transactionTransfers;
        foreach ($transactionTransfers as $transactionTransfer) {
            if ( ! ($transactionTransfer->user instanceof User)) {
                continue;
            }

            TransactionService::createTransaction(
                transactionGateway: 'internal-transactions',
                user: $transactionTransfer->user,
                transactionType: TransactionTypeEnum::Transfer,
                amount: abs($transaction->amount),
                status: TransactionStatusEnum::Pending,
            );
        }
    }

    /**
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            'eloquent.updated: ' . Transaction::class => 'transactionUpdated',
        ];
    }
}
