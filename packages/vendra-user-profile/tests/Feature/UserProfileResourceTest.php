<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Laravel\Pennant\Feature;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Features\ModuleEnabled;
use Misaf\VendraUserProfile\Filament\Resources\Pages\ListUserProfiles;
use Misaf\VendraUserProfile\Filament\Resources\UserProfileResource;
use Misaf\VendraUserProfile\Models\UserProfile;
use Misaf\VendraUserProfile\Tests\Support\UserProfileModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    if ( ! Schema::hasTable('user_profiles')) {
        $migration = require __DIR__ . '/../../database/migrations/create_user_profiles_table.php.stub';
        $migration->up();
    }
});

it('lets the global module switch override a persisted inactive value', function (): void {
    Config::set('vendra-user-profile.features', [
        'enabled'  => true,
        'discover' => false,
    ]);
    Config::set('vendra-user-profile.features_enabled', true);
    Config::set('vendra-user-profile.module_enabled', true);
    $tenant = UserProfileModuleTestContext::createCurrentTenant();
    Feature::for($tenant)->deactivate(ModuleEnabled::class);
    Feature::flushCache();

    expect(UserProfileResource::canAccess())->toBeTrue();
});

it('lets the global feature switch override a persisted active value', function (): void {
    Config::set('vendra-user-profile.features_enabled', false);
    Config::set('vendra-user-profile.module_enabled', false);
    $tenant = UserProfileModuleTestContext::createCurrentTenant();
    Feature::for($tenant)->activate(ModuleEnabled::class);
    Feature::flushCache();

    expect(UserProfileResource::canAccess())->toBeFalse();
});

it('rejects feature scopes that are not tenants', function (): void {
    Config::set('vendra-user-profile.features_enabled', true);
    Config::set('vendra-user-profile.module_enabled', true);
    UserProfileModuleTestContext::createCurrentTenant();

    expect(Feature::for(UserProfileModuleTestContext::createUser())->active(ModuleEnabled::class))->toBeFalse();
});

it('renders user profiles in the Filament resource table', function (): void {
    UserProfileModuleTestContext::setUpFilamentAdminContext();

    $profile = UserProfile::query()->create([
        'user_id'     => User::query()->valueOrFail('id'),
        'name'        => 'Primary Profile',
        'description' => 'Primary user profile',
        'slug'        => 'primary-profile',
        'is_default'  => true,
        'status'      => true,
    ]);

    livewire(ListUserProfiles::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$profile]);
});
