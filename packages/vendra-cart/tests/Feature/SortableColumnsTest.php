<?php

declare(strict_types=1);

use Misaf\VendraCart\Database\Factories\CartFactory;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\Pages\ListCarts;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('sorts the carts table by every sortable column following the stored values', function (): void {
    $first = CartFactory::new()->createOne([
        'expires_at' => now()->addDay(),
        'created_at' => now()->subDays(2),
    ]);

    $second = CartFactory::new()->createOne([
        'expires_at' => now()->addDays(2),
        'created_at' => now()->subDay(),
    ]);

    expect(livewire(ListCarts::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
