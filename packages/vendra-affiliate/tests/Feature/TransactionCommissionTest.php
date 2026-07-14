<?php

declare(strict_types=1);

use Misaf\VendraAffiliate\Database\Factories\AffiliateFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliateReferralFactory;
use Misaf\VendraAffiliate\Enums\CommissionStatusEnum;
use Misaf\VendraAffiliate\Enums\ConversionTypeEnum;
use Misaf\VendraAffiliate\Listeners\TransactionCommissionSubscriber;
use Misaf\VendraAffiliate\Models\AffiliateCommission;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTransaction\Database\Factories\TransactionFactory;
use Misaf\VendraTransaction\Enums\TransactionStatusEnum;
use Misaf\VendraTransaction\Enums\TransactionTypeEnum;
use Misaf\VendraTransaction\Models\Transaction;
use Misaf\VendraUser\Models\User;

beforeEach(function (): void {
    Tenant::factory()->enabled()->create()->makeCurrent();
});

function referredDeposit(int $amount, int $commissionPercent = 20): Transaction
{
    $affiliate = AffiliateFactory::new()
        ->active()
        ->state(['commission_percent' => $commissionPercent])
        ->create();

    $user = User::factory()->create();

    AffiliateReferralFactory::new()
        ->forAffiliate($affiliate)
        ->forUser($user)
        ->create();

    return TransactionFactory::new()->create([
        'user_id'          => $user->id,
        'transaction_type' => TransactionTypeEnum::Deposit,
        'amount'           => $amount,
        'status'           => TransactionStatusEnum::Approved,
    ]);
}

it('credits a commission when a referred deposit is approved', function (): void {
    config()->set('vendra-affiliate.conversions.deposit.enabled', true);

    $transaction = referredDeposit(amount: 10_000, commissionPercent: 20);

    app(TransactionCommissionSubscriber::class)->transactionUpdated($transaction);

    $commission = AffiliateCommission::sole();

    expect($commission->conversion_type)->toBe(ConversionTypeEnum::Deposit)
        ->and($commission->amount)->toBe(2_000)
        ->and($commission->status)->toBe(CommissionStatusEnum::Approved)
        ->and($commission->source_id)->toBe($transaction->id);
});

it('credits a repeated event only once', function (): void {
    config()->set('vendra-affiliate.conversions.deposit.enabled', true);

    $transaction = referredDeposit(amount: 10_000);
    $subscriber = app(TransactionCommissionSubscriber::class);

    $subscriber->transactionUpdated($transaction);
    $subscriber->transactionUpdated($transaction);

    expect(AffiliateCommission::count())->toBe(1);
});

it('reverses the unpaid commission when the deposit leaves the approved state', function (): void {
    config()->set('vendra-affiliate.conversions.deposit.enabled', true);

    $transaction = referredDeposit(amount: 10_000);
    $subscriber = app(TransactionCommissionSubscriber::class);

    $subscriber->transactionUpdated($transaction);

    $transaction->update(['status' => TransactionStatusEnum::Declined]);
    $subscriber->transactionUpdated($transaction->refresh());

    expect(AffiliateCommission::sole()->status)->toBe(CommissionStatusEnum::Reversed);
});

it('ignores deposits when the deposit conversion is disabled', function (): void {
    config()->set('vendra-affiliate.conversions.deposit.enabled', false);

    $transaction = referredDeposit(amount: 10_000);

    app(TransactionCommissionSubscriber::class)->transactionUpdated($transaction);

    expect(AffiliateCommission::count())->toBe(0);
});

it('ignores deposits from users without a referral', function (): void {
    config()->set('vendra-affiliate.conversions.deposit.enabled', true);

    $transaction = TransactionFactory::new()->create([
        'transaction_type' => TransactionTypeEnum::Deposit,
        'amount'           => 10_000,
        'status'           => TransactionStatusEnum::Approved,
    ]);

    app(TransactionCommissionSubscriber::class)->transactionUpdated($transaction);

    expect(AffiliateCommission::count())->toBe(0);
});
