<?php

declare(strict_types=1);

use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;
use Misaf\VendraUser\Database\Factories\UserFactory;
use Misaf\VendraUserProfile\Database\Factories\UserProfileFactory;
use Misaf\VendraUserProfile\Filament\Clusters\Resources\Pages\ListUserProfiles;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

/**
 * The table groups by the owning user's username and Filament keeps the group
 * order ascending regardless of the column sort direction, so the descending
 * pass cannot assert strict ordering across the two groups.
 */
it('sorts the user profiles table by every sortable column following the stored values', function (): void {
    $first = UserProfileFactory::new()->forUser(UserFactory::new()->createOne(['username' => 'aaa-owner']))->createOne(['name' => 'aaa profile']);
    $second = UserProfileFactory::new()->forUser(UserFactory::new()->createOne(['username' => 'bbb-owner']))->createOne(['name' => 'bbb profile']);

    expect(livewire(ListUserProfiles::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second], assertDescendingOrder: false);
});
