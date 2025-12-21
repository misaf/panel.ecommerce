<?php

declare(strict_types=1);

use Misaf\Permission\Database\Seeders\PermissionSeeder;
use Misaf\Permission\Models\Permission;
use Misaf\Permission\Models\Role;
use Misaf\Tenant\Models\Tenant;

test('permission seeder can be run successfully with multiple tenants', function (): void {
    // Create multiple tenants (required for the permission seeder)
    $tenant1 = Tenant::create([
        'name'        => 'Test Tenant 1',
        'description' => 'Test tenant 1 for permission seeder',
        'slug'        => 'test-tenant-1',
        'status'      => true,
    ]);

    $tenant2 = Tenant::create([
        'name'        => 'Test Tenant 2',
        'description' => 'Test tenant 2 for permission seeder',
        'slug'        => 'test-tenant-2',
        'status'      => true,
    ]);

    $tenant3 = Tenant::create([
        'name'        => 'Test Tenant 3',
        'description' => 'Test tenant 3 for permission seeder',
        'slug'        => 'test-tenant-3',
        'status'      => true,
    ]);

    // Run the actual seeder
    $seeder = new PermissionSeeder();
    $seeder->run();

    // Assert that permissions were created for all tenants
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->count())->toBeGreaterThan(0);

    // Assert that roles were created for all tenants
    expect(Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->count())->toBeGreaterThan(0);

    // Assert specific roles exist for each tenant (4 total: 1 default + 3 created)
    expect(Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'super-admin')->count())->toBe(4);
    expect(Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'admin')->count())->toBe(4);
    expect(Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'moderator')->count())->toBe(4);
    expect(Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'user')->count())->toBe(4);

    // Assert specific permissions exist for each tenant (4 total: 1 default + 3 created)
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'view-users')->count())->toBe(4);
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'create-users')->count())->toBe(4);
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'access-admin-panel')->count())->toBe(4);

    // Verify that each tenant has its own set of permissions and roles
    $allTenants = [$this->tenant, $tenant1, $tenant2, $tenant3];
    foreach ($allTenants as $tenant) {
        $tenantPermissions = Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)
            ->where('tenant_id', $tenant->id)
            ->count();
        $tenantRoles = Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)
            ->where('tenant_id', $tenant->id)
            ->count();

        expect($tenantPermissions)->toBeGreaterThan(0);
        expect($tenantRoles)->toBeGreaterThan(0);
    }
});

test('permission seeder can be run successfully with single tenant', function (): void {
    // Clear any existing tenants to start fresh
    Tenant::query()->delete();

    // Create a single tenant (required for the permission seeder)
    $tenant = Tenant::create([
        'name'        => 'Test Tenant',
        'description' => 'Test tenant for permission seeder',
        'slug'        => 'test-tenant',
        'status'      => true,
    ]);

    // Set the current tenant in the app container
    app()->instance('currentTenant', $tenant);

    // Run the actual seeder
    $seeder = new PermissionSeeder();
    $seeder->run();

    // Assert that permissions were created
    expect(Permission::count())->toBeGreaterThan(0);

    // Assert that roles were created
    expect(Role::count())->toBeGreaterThan(0);

    // Assert specific roles exist
    expect(Role::where('name', 'super-admin')->exists())->toBeTrue();
    expect(Role::where('name', 'admin')->exists())->toBeTrue();
    expect(Role::where('name', 'moderator')->exists())->toBeTrue();
    expect(Role::where('name', 'user')->exists())->toBeTrue();

    // Assert specific permissions exist
    expect(Permission::where('name', 'view-users')->exists())->toBeTrue();
    expect(Permission::where('name', 'create-users')->exists())->toBeTrue();
    expect(Permission::where('name', 'access-admin-panel')->exists())->toBeTrue();

    // Assert role-permission relationships
    $superAdmin = Role::where('name', 'super-admin')->first();
    expect($superAdmin->permissions)->toHaveCount(Permission::count());

    $admin = Role::where('name', 'admin')->first();
    expect($admin->permissions->count())->toBeGreaterThan(0);

    $moderator = Role::where('name', 'moderator')->first();
    expect($moderator->permissions->count())->toBeGreaterThan(0);

    $user = Role::where('name', 'user')->first();
    expect($user->permissions->count())->toBeGreaterThan(0);
});

test('permission seeder throws exception when no tenants exist', function (): void {
    // Clear any existing tenants
    Tenant::query()->delete();

    // Run the seeder and expect it to throw an exception
    $seeder = new PermissionSeeder();

    expect(fn() => $seeder->run())->toThrow(Exception::class, 'No tenants found. Please run the tenant seeder first.');
});
