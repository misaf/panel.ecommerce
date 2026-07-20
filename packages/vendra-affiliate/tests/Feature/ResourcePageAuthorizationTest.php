<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraAffiliate\Database\Factories\AffiliateCommissionFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliateFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliatePayoutFactory;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Pages\ViewAffiliateCommission;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Pages\ViewAffiliatePayout;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\CreateAffiliate;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\EditAffiliate;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\ViewAffiliate;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('renders the create affiliate page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateAffiliate::class)
        ->assertOk();
});

it('renders the edit affiliate page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $affiliate = AffiliateFactory::new()->createOne();

    livewire(EditAffiliate::class, ['record' => $affiliate->getKey()])
        ->assertOk();
});

it('renders the view affiliate page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $affiliate = AffiliateFactory::new()->createOne();

    livewire(ViewAffiliate::class, ['record' => $affiliate->getKey()])
        ->assertOk();
});

it('renders the view affiliate commission page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $commission = AffiliateCommissionFactory::new()->createOne();

    livewire(ViewAffiliateCommission::class, ['record' => $commission->getKey()])
        ->assertOk();
});

it('renders the view affiliate payout page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $payout = AffiliatePayoutFactory::new()->createOne();

    livewire(ViewAffiliatePayout::class, ['record' => $payout->getKey()])
        ->assertOk();
});
