<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Misaf\VendraPermission\Models\Role;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;

dataset('monitoring gates', ['viewPulse', 'viewHorizon']);

it('allows anyone in the local environment', function (string $gate): void {
    app()->detectEnvironment(fn(): string => 'local');

    expect(Gate::forUser(null)->allows($gate))->toBeTrue();
})->with('monitoring gates');

it('denies guests outside the local environment', function (string $gate): void {
    app()->detectEnvironment(fn(): string => 'production');

    expect(Gate::forUser(null)->allows($gate))->toBeFalse();
})->with('monitoring gates');

it('denies users without the super admin role outside the local environment', function (string $gate): void {
    app()->detectEnvironment(fn(): string => 'production');

    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    $user = User::factory()->forTenant($tenant)->create();

    expect(Gate::forUser($user)->allows($gate))->toBeFalse();
})->with('monitoring gates');

it('allows super admins outside the local environment', function (string $gate): void {
    app()->detectEnvironment(fn(): string => 'production');

    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();

    $user = User::factory()->forTenant($tenant)->create();
    $user->assignRole(Role::factory()->forTenant($tenant)->forGuard('web')->create([
        'name' => Config::string('vendra-permission.super_admin_role'),
    ]));

    expect(Gate::forUser($user)->allows($gate))->toBeTrue();
})->with('monitoring gates');
