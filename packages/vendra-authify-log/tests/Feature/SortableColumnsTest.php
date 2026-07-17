<?php

declare(strict_types=1);

use Misaf\VendraAuthifyLog\Database\Factories\AuthifyLogFactory;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\Pages\ListAuthifyLogs;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('sorts the authify logs table by every sortable column following the stored values', function (): void {
    $first = AuthifyLogFactory::new()->createOne();
    $second = AuthifyLogFactory::new()->createOne();

    expect(livewire(ListAuthifyLogs::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
