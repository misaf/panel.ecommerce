<?php

declare(strict_types=1);

use Misaf\VendraCurrency\Database\Factories\CurrencyCategoryFactory;
use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\ListCurrencies;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ListCurrencyCategories;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('sorts the currencies table by every sortable column following the stored values', function (): void {
    $currencyCategory = CurrencyCategoryFactory::new()->createOne();

    $first = CurrencyFactory::new()->forCategory($currencyCategory)->createOne();
    $second = CurrencyFactory::new()->forCategory($currencyCategory)->createOne();

    expect(livewire(ListCurrencies::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('sorts the currency categories table by every sortable column following the stored values', function (): void {
    $first = CurrencyCategoryFactory::new()->createOne();
    $second = CurrencyCategoryFactory::new()->createOne();

    expect(livewire(ListCurrencyCategories::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
