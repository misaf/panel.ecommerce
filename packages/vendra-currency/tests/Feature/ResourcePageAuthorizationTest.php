<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraCurrency\Database\Factories\CurrencyCategoryFactory;
use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\CreateCurrency;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\EditCurrency;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\ListCurrencies;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\ViewCurrency;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\CreateCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\EditCurrencyCategory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ListCurrencyCategories;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ViewCurrencyCategory;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
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

it('renders the create currency category page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateCurrencyCategory::class)
        ->assertOk();
});

it('renders the edit currency category page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $category = CurrencyCategoryFactory::new()->createOne();

    livewire(EditCurrencyCategory::class, ['record' => $category->getKey()])
        ->assertOk();
});

it('renders the view currency category page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $category = CurrencyCategoryFactory::new()->createOne();

    livewire(ViewCurrencyCategory::class, ['record' => $category->getKey()])
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

it('renders the reorderable currency categories table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $currencyCategory = CurrencyCategoryFactory::new()->createOne();

    livewire(ListCurrencyCategories::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$currencyCategory]);
});
