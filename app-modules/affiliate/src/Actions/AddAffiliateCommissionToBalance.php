<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Actions;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Misaf\Affiliate\Events\AffiliateCommissionEarnedEvent;
use Misaf\Affiliate\Models\Affiliate;
use Misaf\Affiliate\Models\AffiliateUser;
use Misaf\Transaction\Enums\TransactionStatusEnum;
use Misaf\Transaction\Enums\TransactionTypeEnum;
use Misaf\Transaction\Facades\TransactionService;
use Misaf\Transaction\Models\Transaction;
use Spatie\QueueableAction\QueueableAction;

final class AddAffiliateCommissionToBalance
{
    use QueueableAction;

    /**
     * @param Affiliate $affiliate
     * @return void
     */
    public function execute(Affiliate $affiliate): void
    {
        try {
            $this->processAction($affiliate);

            $affiliate->update(['is_processing' => false]);
        } catch (Exception $e) {
            $this->handleException($affiliate, $e);
        }
    }

    /**
     * @param Affiliate $affiliate
     * @return void
     */
    private function processAction(Affiliate $affiliate): void
    {
        $totalCommission = $this->getTotalCommissionEarned($affiliate);

        if ($totalCommission <= 0) {
            return;
        }

        DB::transaction(function () use ($affiliate, $totalCommission): void {
            if ($this->clearAffiliateCommission($affiliate) <= 0) {
                return;
            }

            event(new AffiliateCommissionEarnedEvent($affiliate->user_id, ($totalCommission * -1)));

            $transaction = $this->createTransaction($affiliate, $totalCommission);

            $this->sendSuccessNotification($affiliate, $transaction->token);
        }, 5);
    }

    /**
     * @param Affiliate $affiliate
     * @return int
     */
    private function getTotalCommissionEarned(Affiliate $affiliate): int
    {
        return (int) AffiliateUser::query()
            ->where('affiliate_id', $affiliate->id)
            ->sum('commission_earned');
    }

    /**
     * @param Affiliate $affiliate
     * @param int $totalCommission
     * @return Transaction
     */
    private function createTransaction(Affiliate $affiliate, int $totalCommission): Transaction
    {
        return TransactionService::createTransaction(
            transactionGateway: 'internal-transactions',
            user: $affiliate->user,
            transactionType: TransactionTypeEnum::Commission,
            amount: abs($totalCommission),
            status: TransactionStatusEnum::Pending,
            metadatas: [
                'type'        => 'commission',
                'description' => __('affiliate.commission_earned'),
            ],
        );
    }

    /**
     * @param Affiliate $affiliate
     * @return int
     */
    private function clearAffiliateCommission(Affiliate $affiliate): int
    {
        return AffiliateUser::query()
            ->whereRelation('affiliate', 'user_id', $affiliate->user_id)
            ->update(['commission_earned' => 0]);
    }

    /**
     * @param Affiliate $affiliate
     * @param string $transactionToken
     * @return void
     */
    private function sendSuccessNotification(Affiliate $affiliate, string $transactionToken): void
    {
        Notification::make()
            ->success()
            ->title(__('Commission successfully processed!'))
            ->body(__('Transaction token: :token', ['token' => $transactionToken]))
            ->broadcast($affiliate->user)
            ->sendToDatabase($affiliate->user);
    }

    /**
     * @param Affiliate $affiliate
     * @return void
     */
    private function sendErrorNotification(Affiliate $affiliate): void
    {
        Notification::make()
            ->danger()
            ->title(__('Commission processing failed.'))
            ->body(__('Please try again later or contact support.'))
            ->broadcast($affiliate->user)
            ->sendToDatabase($affiliate->user);
    }

    /**
     * @param Affiliate $affiliate
     * @param Exception $e
     * @return void
     */
    private function handleException(Affiliate $affiliate, Exception $e): void
    {
        Log::error(sprintf(
            "Error processing commission for user '%s': %s",
            $affiliate->user->username,
            $e->getMessage(),
        ));

        $this->sendErrorNotification($affiliate);
    }
}
