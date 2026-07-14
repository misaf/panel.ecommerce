<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Actions;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Misaf\VendraAffiliate\Enums\CommissionStatusEnum;
use Misaf\VendraAffiliate\Enums\PayoutStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliate\Models\AffiliateCommission;
use Misaf\VendraAffiliate\Models\AffiliatePayout;
use Misaf\VendraTransaction\Enums\TransactionStatusEnum;
use Misaf\VendraTransaction\Enums\TransactionTypeEnum;
use Misaf\VendraTransaction\Facades\TransactionService;
use Misaf\VendraUser\Models\User;
use Spatie\QueueableAction\QueueableAction;

/**
 * Settles an affiliate's approved commissions: groups them into a payout,
 * marks them paid, and credits the balance through a Commission transaction.
 */
final class ProcessAffiliatePayout
{
    use QueueableAction;

    public function execute(Affiliate $affiliate): ?AffiliatePayout
    {
        $payout = DB::transaction(function () use ($affiliate): ?AffiliatePayout {
            $commissions = $affiliate->commissions()
                ->where('status', CommissionStatusEnum::Approved)
                ->whereNull('affiliate_payout_id')
                ->lockForUpdate()
                ->get();

            $amount = (int) $commissions->sum(fn(AffiliateCommission $commission): int => $commission->amount);

            if ($amount < Config::integer('vendra-affiliate.payout.minimum', 0) || 0 === $commissions->count()) {
                return null;
            }

            /** @var AffiliatePayout $payout */
            $payout = $affiliate->payouts()->create([
                'amount' => $amount,
                'status' => PayoutStatusEnum::Pending,
            ]);

            $affiliate->commissions()
                ->whereIn('id', $commissions->modelKeys())
                ->update([
                    'status'              => CommissionStatusEnum::Paid,
                    'affiliate_payout_id' => $payout->id,
                ]);

            return $payout;
        });

        if ( ! $payout instanceof AffiliatePayout) {
            return null;
        }

        $affiliate->loadMissing('user');

        if ( ! $affiliate->user instanceof User) {
            $payout->update(['status' => PayoutStatusEnum::Failed]);

            return $payout;
        }

        $transaction = TransactionService::createTransaction(
            transactionGateway: Config::string('vendra-affiliate.payout.transaction_gateway', 'internal-transactions'),
            user: $affiliate->user,
            transactionType: TransactionTypeEnum::Commission,
            amount: $payout->amount,
            status: TransactionStatusEnum::Pending,
            metadatas: [
                'type'                => 'affiliate-commission',
                'affiliate_payout_id' => $payout->id,
            ],
        );

        $payout->update([
            'status'         => PayoutStatusEnum::Completed,
            'transaction_id' => $transaction->id,
            'processed_at'   => now(),
        ]);

        return $payout;
    }
}
