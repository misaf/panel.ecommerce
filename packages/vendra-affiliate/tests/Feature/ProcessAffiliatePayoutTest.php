<?php

declare(strict_types=1);

use Misaf\VendraAffiliate\Actions\ProcessAffiliatePayout;
use Misaf\VendraAffiliate\Database\Factories\AffiliateCommissionFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliateFactory;
use Misaf\VendraAffiliate\Enums\CommissionStatusEnum;
use Misaf\VendraAffiliate\Enums\PayoutStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTransaction\Database\Factories\TransactionGatewayFactory;
use Misaf\VendraTransaction\Enums\TransactionStatusEnum;
use Misaf\VendraTransaction\Enums\TransactionTypeEnum;
use Misaf\VendraTransaction\Models\Transaction;

beforeEach(function (): void {
    Tenant::factory()->enabled()->create()->makeCurrent();
});

function affiliateWithApprovedBalance(int ...$amounts): Affiliate
{
    $affiliate = AffiliateFactory::new()->active()->create();

    foreach ($amounts as $amount) {
        AffiliateCommissionFactory::new()
            ->forAffiliate($affiliate)
            ->approved()
            ->state(['amount' => $amount])
            ->create();
    }

    return $affiliate;
}

function internalGateway(): void
{
    TransactionGatewayFactory::new()->create([
        'slug'   => [app()->getLocale() => 'internal-transactions'],
        'status' => true,
    ]);
}

it('settles approved commissions into a completed payout and commission transaction', function (): void {
    config()->set('vendra-affiliate.payout.minimum', 1000);
    internalGateway();

    $affiliate = affiliateWithApprovedBalance(1_500, 2_500);

    $payout = app(ProcessAffiliatePayout::class)->execute($affiliate);

    expect($payout)->not->toBeNull()
        ->and($payout->amount)->toBe(4_000)
        ->and($payout->status)->toBe(PayoutStatusEnum::Completed)
        ->and($payout->processed_at)->not->toBeNull()
        ->and($affiliate->commissions()->where('status', CommissionStatusEnum::Paid)->count())->toBe(2)
        ->and($affiliate->pendingBalance())->toBe(0);

    $transaction = Transaction::findOrFail($payout->transaction_id);

    expect($transaction->transaction_type)->toBe(TransactionTypeEnum::Commission)
        ->and($transaction->amount)->toBe(4_000)
        ->and($transaction->status)->toBe(TransactionStatusEnum::Pending)
        ->and($transaction->user_id)->toBe($affiliate->user_id);
});

it('refuses to pay out below the configured minimum', function (): void {
    config()->set('vendra-affiliate.payout.minimum', 5_000);

    $affiliate = affiliateWithApprovedBalance(1_000);

    expect(app(ProcessAffiliatePayout::class)->execute($affiliate))->toBeNull()
        ->and($affiliate->pendingBalance())->toBe(1_000);
});

it('does not pay pending or reversed commissions', function (): void {
    config()->set('vendra-affiliate.payout.minimum', 0);
    internalGateway();

    $affiliate = AffiliateFactory::new()->active()->create();

    AffiliateCommissionFactory::new()->forAffiliate($affiliate)->pending()->state(['amount' => 900])->create();
    AffiliateCommissionFactory::new()->forAffiliate($affiliate)->reversed()->state(['amount' => 900])->create();
    AffiliateCommissionFactory::new()->forAffiliate($affiliate)->approved()->state(['amount' => 700])->create();

    $payout = app(ProcessAffiliatePayout::class)->execute($affiliate);

    expect($payout->amount)->toBe(700);
});
