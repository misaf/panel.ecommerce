<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Laravel\Pennant\Feature;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Database\Factories\UserProfileFactory;
use Misaf\VendraUserProfile\Enums\UserProfileFeatureEnum;
use Misaf\VendraUserProfile\Filament\Resources\Pages\ListUserProfiles;
use Misaf\VendraUserProfile\Filament\Resources\UserProfileResource;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    if ( ! Schema::hasTable('user_profiles')) {
        $migration = require __DIR__ . '/../../database/migrations/create_user_profiles_table.php.stub';
        $migration->up();
    }
});

it('resolves resource access from the configured module default', function (): void {
    Config::set('vendra-user-profile.features.enabled', true);
    Config::set('vendra-user-profile.features.defaults', [
        UserProfileFeatureEnum::MODULE_ENABLED->value => true,
    ]);
    PermissionModuleTestContext::createCurrentTenant();

    expect(UserProfileResource::canAccess())->toBeTrue();
});

it('rejects feature scopes that are not tenants', function (): void {
    Config::set('vendra-user-profile.features.enabled', true);
    Config::set('vendra-user-profile.features.defaults', [
        UserProfileFeatureEnum::MODULE_ENABLED->value => true,
    ]);
    PermissionModuleTestContext::createCurrentTenant();

    expect(Feature::for(User::factory()->create())->active(UserProfileFeatureEnum::MODULE_ENABLED->value))->toBeFalse();
});

it('renders user profiles in the Filament resource table', function (): void {
    $tenant = PermissionModuleTestContext::setUpFilamentAdminContext();
    Feature::for($tenant)->activate(UserProfileFeatureEnum::MODULE_ENABLED->value);

    $profile = UserProfileFactory::new()
        ->forTenant($tenant)
        ->createOne();

    livewire(ListUserProfiles::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$profile]);
});
