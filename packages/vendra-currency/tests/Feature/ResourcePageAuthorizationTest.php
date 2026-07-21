<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\CreateCurrency;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\EditCurrency;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\ListCurrencies;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\ViewCurrency;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('renders the create currency page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateCurrency::class)
        ->assertOk();
});

it('renders the edit currency page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $currency = CurrencyFactory::new()->createOne();

    livewire(EditCurrency::class, ['record' => $currency->getKey()])
        ->assertOk();
});

it('renders the view currency page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $currency = CurrencyFactory::new()->createOne();

    livewire(ViewCurrency::class, ['record' => $currency->getKey()])
        ->assertOk();
});

it('renders the reorderable currencies table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $currency = CurrencyFactory::new()->createOne();

    livewire(ListCurrencies::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$currency]);
});
