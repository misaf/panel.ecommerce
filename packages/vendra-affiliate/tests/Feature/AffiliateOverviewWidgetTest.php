<?php

declare(strict_types=1);

use Filament\Panel;
use Misaf\VendraAffiliate\AffiliatePlugin;
use Misaf\VendraAffiliate\Database\Factories\AffiliateClickFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliateCommissionFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliateFactory;
use Misaf\VendraAffiliate\Database\Factories\AffiliateReferralFactory;
use Misaf\VendraAffiliate\Filament\Widgets\AffiliateOverviewWidget;
use Misaf\VendraAffiliate\Filament\Widgets\UserAffiliateOverviewWidget;
use Misaf\VendraTenant\Database\Factories\TenantFactory;
use Misaf\VendraUser\Database\Factories\UserFactory;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('registers admin management separately from the personal user widget', function (): void {
    $adminPanel = Panel::make()->id('admin');
    $userPanel = Panel::make()->id('user');

    AffiliatePlugin::make()->register($adminPanel);
    AffiliatePlugin::make()->register($userPanel);

    expect($adminPanel->getWidgets())->toContain(AffiliateOverviewWidget::class);
    expect(in_array(UserAffiliateOverviewWidget::class, $adminPanel->getWidgets(), true))->toBeFalse();
    expect($adminPanel->getClusters())->not->toBeEmpty();
    expect($userPanel->getWidgets())->toContain(UserAffiliateOverviewWidget::class);
    expect(in_array(AffiliateOverviewWidget::class, $userPanel->getWidgets(), true))->toBeFalse();
    expect($userPanel->getClusters())->toBeEmpty();
});

it('shows tenant-scoped affiliate metrics and earned commissions', function (): void {
    $tenant = TenantFactory::new()->enabled()->createOne();
    $tenant->makeCurrent();

    $affiliate = AffiliateFactory::new()->createOne();

    AffiliateClickFactory::new()
        ->forAffiliate($affiliate)
        ->count(2)
        ->create();

    AffiliateReferralFactory::new()
        ->forAffiliate($affiliate)
        ->createOne();

    AffiliateCommissionFactory::new()
        ->forAffiliate($affiliate)
        ->approved()
        ->state(['amount' => 1_250])
        ->createOne();

    AffiliateCommissionFactory::new()
        ->forAffiliate($affiliate)
        ->paid()
        ->state(['amount' => 2_750])
        ->createOne();

    AffiliateCommissionFactory::new()
        ->forAffiliate($affiliate)
        ->pending()
        ->state(['amount' => 8_000])
        ->createOne();

    AffiliateCommissionFactory::new()
        ->forAffiliate($affiliate)
        ->reversed()
        ->state(['amount' => 16_000])
        ->createOne();

    $otherTenant = TenantFactory::new()->enabled()->createOne();
    $otherTenant->makeCurrent();

    $otherAffiliate = AffiliateFactory::new()->createOne();

    AffiliateClickFactory::new()
        ->forAffiliate($otherAffiliate)
        ->count(3)
        ->create();

    AffiliateReferralFactory::new()
        ->forAffiliate($otherAffiliate)
        ->count(2)
        ->create();

    AffiliateCommissionFactory::new()
        ->forAffiliate($otherAffiliate)
        ->approved()
        ->state(['amount' => 32_000])
        ->createOne();

    $tenant->makeCurrent();

    livewire(AffiliateOverviewWidget::class)
        ->assertSeeInOrder([
            __('vendra-affiliate::widgets.affiliate_click_stats'),
            '2',
            __('vendra-affiliate::widgets.affiliate_referral_stats'),
            '1',
            __('vendra-affiliate::widgets.affiliate_commission_stats'),
            '4,000',
        ]);
});

it('shows only the signed-in users affiliate metrics', function (): void {
    TenantFactory::new()->enabled()->createOne()->makeCurrent();

    $user = UserFactory::new()->createOne();
    $affiliate = AffiliateFactory::new()->forUser($user)->createOne();

    AffiliateClickFactory::new()
        ->forAffiliate($affiliate)
        ->count(2)
        ->create();

    AffiliateReferralFactory::new()
        ->forAffiliate($affiliate)
        ->createOne();

    AffiliateCommissionFactory::new()
        ->forAffiliate($affiliate)
        ->approved()
        ->state(['amount' => 1_500])
        ->createOne();

    AffiliateCommissionFactory::new()
        ->forAffiliate($affiliate)
        ->paid()
        ->state(['amount' => 2_500])
        ->createOne();

    AffiliateCommissionFactory::new()
        ->forAffiliate($affiliate)
        ->pending()
        ->state(['amount' => 8_000])
        ->createOne();

    $otherAffiliate = AffiliateFactory::new()->createOne();

    AffiliateClickFactory::new()
        ->forAffiliate($otherAffiliate)
        ->count(3)
        ->create();

    AffiliateReferralFactory::new()
        ->forAffiliate($otherAffiliate)
        ->count(2)
        ->create();

    AffiliateCommissionFactory::new()
        ->forAffiliate($otherAffiliate)
        ->approved()
        ->state(['amount' => 32_000])
        ->createOne();

    actingAs($user);

    expect(UserAffiliateOverviewWidget::canView())->toBeTrue();

    livewire(UserAffiliateOverviewWidget::class)
        ->assertSeeInOrder([
            __('vendra-affiliate::widgets.affiliate_click_stats'),
            '2',
            __('vendra-affiliate::widgets.affiliate_referral_stats'),
            '1',
            __('vendra-affiliate::widgets.affiliate_commission_stats'),
            '4,000',
        ]);
});

it('hides the personal affiliate widget from users without an affiliate', function (): void {
    TenantFactory::new()->enabled()->createOne()->makeCurrent();

    actingAs(UserFactory::new()->createOne());

    expect(UserAffiliateOverviewWidget::canView())->toBeFalse();
});
