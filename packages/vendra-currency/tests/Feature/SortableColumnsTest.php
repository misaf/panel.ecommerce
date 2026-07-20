<?php

declare(strict_types=1);

use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Misaf\VendraCurrency\Database\Factories\CurrencyCategoryFactory;
use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\ListCurrencies;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages\ListCurrencyCategories;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('sorts the currencies table by every sortable column following the stored values', function (): void {
    $currencyCategory = CurrencyCategoryFactory::new()->createOne();

    $first = CurrencyFactory::new()->forCategory($currencyCategory)->createOne();
    $second = CurrencyFactory::new()->forCategory($currencyCategory)->createOne();

    $component = livewire(ListCurrencies::class)->call('loadTable');

    expect($component)
        ->toSortByEverySortableColumn([$first, $second])
        ->and($component->instance()->getTable()->getDefaultGroup())->toBeNull();
});

it('renders the ISO code as a currency suffix badge', function (): void {
    $currencyCategory = CurrencyCategoryFactory::new()->createOne();
    $currency = CurrencyFactory::new()->forCategory($currencyCategory)->createOne([
        'iso_code' => 'TST',
    ]);

    livewire(ListCurrencies::class)
        ->call('loadTable')
        ->assertTableColumnExists(
            'name',
            function (BadgeableColumn $column): bool {
                $formattedState = (string) $column->formatState('Test currency');

                return str_contains($formattedState, 'badgeable-column-badge')
                    && str_contains($formattedState, 'TST');
            },
            $currency,
        );
});

it('sorts the currency categories table by every sortable column following the stored values', function (): void {
    $first = CurrencyCategoryFactory::new()->createOne();
    $second = CurrencyCategoryFactory::new()->createOne();

    expect(livewire(ListCurrencyCategories::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('preloads currency counts for category badges', function (): void {
    $category = CurrencyCategoryFactory::new()->createOne();
    CurrencyFactory::new()->count(2)->forCategory($category)->create();

    $records = livewire(ListCurrencyCategories::class)
        ->call('loadTable')
        ->instance()
        ->getTableRecords()
        ->getCollection();

    expect($records->firstWhere('id', $category->id)?->getAttribute('currencies_count'))->toBe(2);

    livewire(ListCurrencyCategories::class)
        ->call('loadTable')
        ->assertTableColumnExists(
            'name',
            function (BadgeableColumn $column): bool {
                $formattedState = (string) $column->formatState('Category');

                return str_contains($formattedState, 'badgeable-column-badge')
                    && str_contains($formattedState, '2');
            },
            $category,
        );
});
