<?php

declare(strict_types=1);

use Misaf\Permission\Database\Seeders\PermissionSeeder;
use Misaf\Permission\Models\Permission;
use Misaf\Permission\Models\Role;
use Misaf\Tenant\Models\Tenant;

test('permission seeder creates expected permissions and roles for multiple tenants', function (): void {
    // This test verifies that the seeder works correctly with multiple tenants

    // Create multiple tenants
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

    $tenants = [$tenant1, $tenant2, $tenant3];

    // Run the actual seeder
    $seeder = new PermissionSeeder();
    $seeder->run();

    // Assert that permissions were created for all tenants (4 total: 1 default + 3 created)
    $expectedPermissionCount = count(getExpectedPermissions()) * 4; // 4 tenants total
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->count())->toBe($expectedPermissionCount);

    // Assert that roles were created for all tenants (4 total: 1 default + 3 created)
    $expectedRoleCount = count(getExpectedRoles()) * 4; // 4 tenants total
    expect(Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->count())->toBe($expectedRoleCount);

    // Verify each tenant has the correct structure
    $allTenants = [$this->tenant, $tenant1, $tenant2, $tenant3];
    foreach ($allTenants as $tenant) {
        verifyTenantStructure($tenant);
    }
});

test('permission seeder creates expected permissions and roles for single tenant', function (): void {
    // This test verifies that the seeder works correctly with a single tenant

    // Clear any existing tenants to start fresh
    Tenant::query()->delete();

    // Create a single tenant
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

    // Verify the tenant structure
    verifyTenantStructure($tenant);
});

/**
 * Verify the structure for a specific tenant.
 */
function verifyTenantStructure(Tenant $tenant): void
{
    $permissions = getExpectedPermissions();
    $roles = getExpectedRoles();

    // Assert that permissions were created for this tenant
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('tenant_id', $tenant->id)->count())->toBe(count($permissions));

    // Assert that roles were created for this tenant
    expect(Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('tenant_id', $tenant->id)->count())->toBe(count($roles));

    // Assert Super Admin role exists
    $superAdmin = Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'super-admin')->where('tenant_id', $tenant->id)->first();
    expect($superAdmin)->not->toBeNull();
    expect($superAdmin->name)->toBe('super-admin');

    // Assert Admin role exists
    $admin = Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'admin')->where('tenant_id', $tenant->id)->first();
    expect($admin)->not->toBeNull();
    expect($admin->name)->toBe('admin');

    // Assert Moderator role exists
    $moderator = Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'moderator')->where('tenant_id', $tenant->id)->first();
    expect($moderator)->not->toBeNull();
    expect($moderator->name)->toBe('moderator');

    // Assert User role exists
    $user = Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'user')->where('tenant_id', $tenant->id)->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('user');

    // Assert specific permissions exist for this tenant
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'view-users')->where('tenant_id', $tenant->id)->exists())->toBeTrue();
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'create-users')->where('tenant_id', $tenant->id)->exists())->toBeTrue();
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'access-admin-panel')->where('tenant_id', $tenant->id)->exists())->toBeTrue();
    expect(Permission::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('name', 'manage-system-settings')->where('tenant_id', $tenant->id)->exists())->toBeTrue();
}

/**
 * Get the expected permissions array.
 */
function getExpectedPermissions(): array
{
    return [
        // User permissions
        'view-users'             => 'View users',
        'create-users'           => 'Create users',
        'update-users'           => 'Update users',
        'delete-users'           => 'Delete users',
        'delete-any-users'       => 'Delete any users',
        'force-delete-users'     => 'Force delete users',
        'force-delete-any-users' => 'Force delete any users',
        'restore-users'          => 'Restore users',
        'restore-any-users'      => 'Restore any users',
        'replicate-users'        => 'Replicate users',
        'view-any-users'         => 'View any users',

        // Role permissions
        'view-roles'             => 'View roles',
        'create-roles'           => 'Create roles',
        'update-roles'           => 'Update roles',
        'delete-roles'           => 'Delete roles',
        'delete-any-roles'       => 'Delete any roles',
        'force-delete-roles'     => 'Force delete roles',
        'force-delete-any-roles' => 'Force delete any roles',
        'restore-roles'          => 'Restore roles',
        'restore-any-roles'      => 'Restore any roles',
        'replicate-roles'        => 'Replicate roles',
        'view-any-roles'         => 'View any roles',

        // Permission permissions
        'view-permissions'             => 'View permissions',
        'create-permissions'           => 'Create permissions',
        'update-permissions'           => 'Update permissions',
        'delete-permissions'           => 'Delete permissions',
        'delete-any-permissions'       => 'Delete any permissions',
        'force-delete-permissions'     => 'Force delete permissions',
        'force-delete-any-permissions' => 'Force delete any permissions',
        'restore-permissions'          => 'Restore permissions',
        'restore-any-permissions'      => 'Restore any permissions',
        'replicate-permissions'        => 'Replicate permissions',
        'view-any-permissions'         => 'View any permissions',

        // Tenant permissions
        'view-tenants'             => 'View tenants',
        'create-tenants'           => 'Create tenants',
        'update-tenants'           => 'Update tenants',
        'delete-tenants'           => 'Delete tenants',
        'delete-any-tenants'       => 'Delete any tenants',
        'force-delete-tenants'     => 'Force delete tenants',
        'force-delete-any-tenants' => 'Force delete any tenants',
        'restore-tenants'          => 'Restore tenants',
        'restore-any-tenants'      => 'Restore any tenants',
        'replicate-tenants'        => 'Replicate tenants',
        'view-any-tenants'         => 'View any tenants',

        // System permissions
        'access-admin-panel'     => 'Access admin panel',
        'manage-system-settings' => 'Manage system settings',
        'view-system-logs'       => 'View system logs',
        'manage-backups'         => 'Manage backups',
    ];
}

/**
 * Get the expected roles array.
 */
function getExpectedRoles(): array
{
    return [
        'super-admin' => 'Super Administrator',
        'admin'       => 'Administrator',
        'moderator'   => 'Moderator',
        'user'        => 'User',
    ];
}
