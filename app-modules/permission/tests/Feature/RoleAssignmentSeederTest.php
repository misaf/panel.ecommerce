<?php

declare(strict_types=1);

use Misaf\Permission\Database\Seeders\RoleAssignmentSeeder;
use Misaf\Permission\Models\Role;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;

test('role assignment seeder can assign individual roles to users', function (): void {
    // Create users for the tenant
    $user1 = User::factory()->forTenant($this->tenant)->create([
        'username'          => 'user1',
        'email'             => 'user1@example.com',
        'email_verified_at' => now(),
    ]);

    $user2 = User::factory()->forTenant($this->tenant)->create([
        'username'          => 'user2',
        'email'             => 'user2@example.com',
        'email_verified_at' => null,
    ]);

    // Run the seeder
    $seeder = new RoleAssignmentSeeder();
    $seeder->run();

    // Assert that individual roles were created and assigned correctly
    expect($user1->roles)->toHaveCount(1);
    expect($user2->roles)->toHaveCount(1);

    $role1Name = $user1->roles->first()->name;
    $role2Name = $user2->roles->first()->name;

    expect($role1Name)->toBe("user_{$user1->id}_user1");
    expect($role2Name)->toBe("user_{$user2->id}_user2");

    // Verify roles are unique
    expect($role1Name)->not->toBe($role2Name);

    // Verify roles belong to the correct tenant
    expect($user1->roles->first()->tenant_id)->toBe($this->tenant->id);
    expect($user2->roles->first()->tenant_id)->toBe($this->tenant->id);
});

test('role assignment seeder skips users that already have roles', function (): void {
    // Create a user
    $user = User::factory()->forTenant($this->tenant)->create([
        'username'          => 'existinguser',
        'email'             => 'existing@example.com',
        'email_verified_at' => now(),
    ]);

    // Manually assign a role first
    $existingRole = Role::factory()->forTenant($this->tenant)->create([
        'name'       => 'custom_role',
        'guard_name' => 'web',
    ]);
    $user->assignRole($existingRole);

    expect($user->hasRole('custom_role'))->toBeTrue();

    // Run the seeder
    $seeder = new RoleAssignmentSeeder();
    $seeder->run();

    // Assert that the user still has the original role and wasn't changed
    expect($user->roles)->toHaveCount(1);
    expect($user->roles->first()->name)->toBe('custom_role');
    expect($user->hasRole('custom_role'))->toBeTrue();
});

test('role assignment seeder handles multiple tenants', function (): void {
    // Create a second tenant
    $tenant2 = Tenant::create([
        'name'        => 'Test Tenant 2',
        'description' => 'Test tenant 2 for role assignment',
        'slug'        => 'test-tenant-2',
        'status'      => true,
    ]);

    // Create users for both tenants
    $user1 = User::factory()->forTenant($this->tenant)->create([
        'username'          => 'user1',
        'email'             => 'user1@example.com',
        'email_verified_at' => now(),
    ]);

    // Debug: Check after first user creation
    dump([
        'after_user1' => [
            'total_users'     => User::count(),
            'user1_tenant_id' => $user1->tenant_id,
        ]
    ]);

    // Temporarily set the current tenant to tenant2 for creating the second user
    $originalTenant = Tenant::current();
    app()->instance('currentTenant', $tenant2);

    try {
        $user2 = User::factory()->forTenant($tenant2)->create([
            'username'          => 'user2',
            'email'             => 'user2@example.com',
            'email_verified_at' => now(),
        ]);
        dump(['user2_created' => true, 'user2_id' => $user2->id, 'user2_tenant_id' => $user2->tenant_id]);

        // Immediately check if user2 exists in database
        $user2FromDb = User::find($user2->id);
        dump(['user2_from_db' => $user2FromDb ? ['id' => $user2FromDb->id, 'tenant_id' => $user2FromDb->tenant_id] : null]);

    } catch (Exception $e) {
        dump(['user2_creation_failed' => $e->getMessage()]);
        throw $e;
    }

    // Restore the original tenant
    app()->instance('currentTenant', $originalTenant);

    // Debug: Check user tenant IDs
    dump([
        'tenant1_id'        => $this->tenant->id,
        'tenant2_id'        => $tenant2->id,
        'user1_tenant_id'   => $user1->tenant_id,
        'user2_tenant_id'   => $user2->tenant_id,
        'total_users'       => User::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->count(),
        'users_for_tenant1' => User::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('tenant_id', $this->tenant->id)->count(),
        'users_for_tenant2' => User::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('tenant_id', $tenant2->id)->count(),
        'all_users'         => User::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->get()->map(fn($u) => ['id' => $u->id, 'tenant_id' => $u->tenant_id, 'email' => $u->email])->toArray(),
    ]);

    // Run the seeder
    $seeder = new RoleAssignmentSeeder();
    $seeder->run();

    // Debug: Check what roles were created
    dump([
        'total_roles'       => Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->count(),
        'roles_for_tenant1' => Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('tenant_id', $this->tenant->id)->count(),
        'roles_for_tenant2' => Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->where('tenant_id', $tenant2->id)->count(),
        'all_roles'         => Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->get()->map(fn($r) => ['id' => $r->id, 'tenant_id' => $r->tenant_id, 'name' => $r->name])->toArray(),
        'model_has_roles'   => Illuminate\Support\Facades\DB::table('model_has_roles')->get()->map(fn($r) => ['role_id' => $r->role_id, 'model_id' => $r->model_id, 'model_type' => $r->model_type])->toArray(),
    ]);

    // Refresh user objects to get updated role relationships
    $user1->refresh();
    $user2->refresh();

    // Debug: Check user roles after refresh
    dump([
        'user1_roles_count' => $user1->roles()->count(),
        'user2_roles_count' => $user2->roles()->count(),
        'user1_roles'       => $user1->roles->map(fn($r) => ['id' => $r->id, 'name' => $r->name])->toArray(),
        'user2_roles'       => $user2->roles->map(fn($r) => ['id' => $r->id, 'name' => $r->name])->toArray(),
    ]);

    // Assert that individual roles were created for both tenants
    expect($user1->roles)->toHaveCount(1);
    expect($user2->roles)->toHaveCount(1);

    $role1Name = $user1->roles->first()->name;
    $role2Name = $user2->roles->first()->name;

    expect($role1Name)->toBe("user_{$user1->id}_user1");
    expect($role2Name)->toBe("user_{$user2->id}_user2");

    // Debug: Let's see what tenant IDs we have
    dump([
        'tenant1_id'      => $this->tenant->id,
        'tenant2_id'      => $tenant2->id,
        'user1_tenant_id' => $user1->tenant_id,
        'user2_tenant_id' => $user2->tenant_id,
        'role1_tenant_id' => $user1->roles->first()->tenant_id,
        'role2_tenant_id' => $user2->roles->first()->tenant_id,
    ]);

    // Verify roles belong to the correct tenants
    expect($user1->roles->first()->tenant_id)->toBe($this->tenant->id);
    expect($user2->roles->first()->tenant_id)->toBe($tenant2->id);
});

test('role assignment seeder throws exception when no tenants exist', function (): void {
    // Clear all tenants
    Tenant::query()->delete();

    // Run the seeder and expect it to throw an exception
    $seeder = new RoleAssignmentSeeder();

    expect(fn() => $seeder->run())->toThrow(Exception::class, 'No tenants found. Please run the tenant seeder first.');
});
