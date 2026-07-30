<?php

declare(strict_types=1);

use Misaf\VendraAffiliate\Database\Factories\AffiliateCommissionFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliateFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliatePayoutFactory;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Pages\ListAffiliateCommissions;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Pages\ListAffiliatePayouts;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\ListAffiliates;
use Misaf\VendraUser\Database\Factories\UserFactory;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentAdminTestContext();
});

it('sorts the affiliates table by every sortable column following the stored values', function (): void {
    $first = AffiliateFactory::new()->forUser(UserFactory::new()->createOne(['username' => 'aaa-affiliate']))->createOne(['commission_percent' => 5]);
    $second = AffiliateFactory::new()->forUser(UserFactory::new()->createOne(['username' => 'bbb-affiliate']))->createOne(['commission_percent' => 40]);

    expect(livewire(ListAffiliates::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('loads pending affiliate balances as a table aggregate', function (): void {
    $affiliate = AffiliateFactory::new()->createOne();

    AffiliateCommissionFactory::new()->forAffiliate($affiliate)->approved()->createOne(['amount' => 1_500]);
    AffiliateCommissionFactory::new()->forAffiliate($affiliate)->approved()->createOne(['amount' => 2_500]);
    AffiliateCommissionFactory::new()->forAffiliate($affiliate)->pending()->createOne(['amount' => 9_000]);

    livewire(ListAffiliates::class)
        ->call('loadTable')
        ->assertTableColumnStateSet('pending_balance', 4_000, $affiliate);
});

it('sorts the affiliate commissions table by every sortable column following the stored values', function (): void {
    $first = AffiliateCommissionFactory::new()->forAffiliate(AffiliateFactory::new()->createOne(['code' => 'AAA11111']))->createOne(['amount' => 100]);
    $second = AffiliateCommissionFactory::new()->forAffiliate(AffiliateFactory::new()->createOne(['code' => 'BBB22222']))->createOne(['amount' => 90_000]);

    expect(livewire(ListAffiliateCommissions::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('sorts the affiliate payouts table by every sortable column following the stored values', function (): void {
    $first = AffiliatePayoutFactory::new()->forAffiliate(AffiliateFactory::new()->createOne(['code' => 'AAA11111']))->createOne(['amount' => 1_000]);
    $second = AffiliatePayoutFactory::new()->forAffiliate(AffiliateFactory::new()->createOne(['code' => 'BBB22222']))->createOne(['amount' => 400_000]);

    expect(livewire(ListAffiliatePayouts::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
