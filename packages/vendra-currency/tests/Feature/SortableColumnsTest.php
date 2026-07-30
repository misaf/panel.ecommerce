<?php

declare(strict_types=1);

use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Pages\ListCurrencies;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentAdminTestContext();
});

it('sorts the currencies table by every sortable column following the stored values', function (): void {
    $first = CurrencyFactory::new()->code('AUD')->createOne(['position' => 1]);
    $second = CurrencyFactory::new()->code('ZAR')->createOne(['position' => 2]);

    expect(livewire(ListCurrencies::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
