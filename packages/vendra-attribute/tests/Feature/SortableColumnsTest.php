<?php

declare(strict_types=1);

use Misaf\VendraAttribute\Database\Factories\AttributeFactory;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\ListAttributes;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('sorts the attributes table by every sortable column following the stored values', function (): void {
    $first = AttributeFactory::new()->createOne(['name' => 'aaa attribute']);
    $second = AttributeFactory::new()->createOne(['name' => 'bbb attribute']);

    expect(livewire(ListAttributes::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
