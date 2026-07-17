<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;
use Misaf\VendraUserProfile\Database\Factories\UserProfileFactory;
use Misaf\VendraUserProfile\Filament\Clusters\Resources\Pages\CreateUserProfile;
use Misaf\VendraUserProfile\Filament\Clusters\Resources\Pages\EditUserProfile;
use Misaf\VendraUserProfile\Filament\Clusters\Resources\Pages\ViewUserProfile;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('renders the create user profile page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateUserProfile::class)
        ->assertOk();
});

it('renders the edit user profile page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $userProfile = UserProfileFactory::new()->createOne();

    livewire(EditUserProfile::class, ['record' => $userProfile->getKey()])
        ->assertOk();
});

it('renders the view user profile page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $userProfile = UserProfileFactory::new()->createOne();

    livewire(ViewUserProfile::class, ['record' => $userProfile->getKey()])
        ->assertOk();
});
