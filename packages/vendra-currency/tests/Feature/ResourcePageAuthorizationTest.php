<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;
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

it('validates currency values against their database column types', function (): void {
    $category = CurrencyCategoryFactory::new()->createOne();

    livewire(CreateCurrency::class)
        ->fillForm([
            'currency_category_id' => $category->getKey(),
            'name'                 => 'Invalid Currency',
            'slug'                 => 'invalid-currency',
            'iso_code'             => 'INV',
            'conversion_rate'      => 'not-numeric',
            'decimal_place'        => 256,
            'buy_price'            => 100,
            'sell_price'           => 100,
            'description'          => Str::repeat('a', 256),
            'is_default'           => false,
            'status'               => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'conversion_rate' => 'numeric',
            'decimal_place'   => 'max',
            'description'     => 'max',
        ]);
});

it('limits scalar currency category fields to their database lengths', function (): void {
    livewire(CreateCurrencyCategory::class)
        ->fillForm([
            'name'        => Str::repeat('n', 256),
            'slug'        => Str::repeat('s', 256),
            'description' => Str::repeat('d', 256),
            'status'      => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'max',
            'slug' => 'max',
        ])
        ->assertFormFieldExists(
            'description',
            fn(Textarea $field): bool => 255 === $field->getMaxLength(),
        );
});
