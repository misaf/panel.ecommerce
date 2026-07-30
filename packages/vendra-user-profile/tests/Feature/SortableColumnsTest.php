<?php

declare(strict_types=1);

use Misaf\VendraUser\Database\Factories\UserFactory;
use Misaf\VendraUserProfile\Database\Factories\UserProfileFactory;
use Misaf\VendraUserProfile\Filament\Clusters\Resources\Pages\ListUserProfiles;
use Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentAdminTestContext([UserProfileResource::class]);
});

it('sorts the user profiles table by every sortable column following the stored values', function (): void {
    $first = UserProfileFactory::new()->forUser(UserFactory::new()->createOne(['username' => 'aaa-owner']))->createOne(['name' => 'aaa profile']);
    $second = UserProfileFactory::new()->forUser(UserFactory::new()->createOne(['username' => 'bbb-owner']))->createOne(['name' => 'bbb profile']);

    $component = livewire(ListUserProfiles::class)->call('loadTable');

    expect($component)
        ->toSortByEverySortableColumn([$first, $second])
        ->and($component->instance()->getTable()->getDefaultGroup())->toBeNull();
});
