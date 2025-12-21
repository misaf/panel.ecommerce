<?php

declare(strict_types=1);

use Misaf\Permission\Models\Permission;
use Misaf\Permission\Models\Role;

test('role can be created with factory', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();

    expect($role)->toBeInstanceOf(Role::class);
    expect($role->name)->toBeString();
    expect($role->guard_name)->toBeString();
    expect($role->tenant_id)->toBe($this->tenant->id); // Will be set by BelongsToTenant trait
});

test('role can be created manually', function (): void {
    $role = Role::create([
        'name'       => 'admin',
        'guard_name' => 'web',
        'tenant_id'  => $this->tenant->id,
    ]);

    expect($role->name)->toBe('admin');
    expect($role->guard_name)->toBe('web');
    expect($role->id)->toBeInt();
    expect($role->tenant_id)->toBe($this->tenant->id);
});

test('role belongs to tenant', function (): void {
    $role = Role::create([
        'name'       => 'admin',
        'guard_name' => 'web',
        'tenant_id'  => $this->tenant->id,
    ]);

    // The BelongsToTenant trait should automatically set the tenant_id
    expect($role->tenant_id)->toBe($this->tenant->id);
});

test('role can be soft deleted', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();

    expect(Role::count())->toBe(1);

    $role->delete();

    expect(Role::count())->toBe(0);
    expect(Role::withTrashed()->count())->toBe(1);
    expect($role->trashed())->toBeTrue();
});

test('role can be restored after soft delete', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();

    $role->delete();
    expect(Role::count())->toBe(0);

    $role->restore();
    expect(Role::count())->toBe(1);
    expect($role->trashed())->toBeFalse();
});

test('role can be force deleted', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();

    expect(Role::count())->toBe(1);

    $role->forceDelete();

    expect(Role::count())->toBe(0);
    expect(Role::withTrashed()->count())->toBe(0);
});

test('role can have permissions', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    $role->givePermissionTo($permission);

    expect($role->hasPermissionTo($permission))->toBeTrue();
    expect($role->permissions)->toHaveCount(1);
    expect($role->permissions->first()->id)->toBe($permission->id);
});

test('role can have multiple permissions', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();
    $permission1 = Permission::factory()->forTenant($this->tenant)->create();
    $permission2 = Permission::factory()->forTenant($this->tenant)->create();
    $permission3 = Permission::factory()->forTenant($this->tenant)->create();

    $role->givePermissionTo([$permission1, $permission2, $permission3]);

    expect($role->permissions)->toHaveCount(3);
    expect($role->hasPermissionTo($permission1))->toBeTrue();
    expect($role->hasPermissionTo($permission2))->toBeTrue();
    expect($role->hasPermissionTo($permission3))->toBeTrue();
});

test('role can revoke permissions', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    $role->givePermissionTo($permission);
    expect($role->hasPermissionTo($permission))->toBeTrue();

    $role->revokePermissionTo($permission);
    expect($role->hasPermissionTo($permission))->toBeFalse();
    expect($role->permissions)->toHaveCount(0);
});

test('role can sync permissions', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();
    $permission1 = Permission::factory()->forTenant($this->tenant)->create();
    $permission2 = Permission::factory()->forTenant($this->tenant)->create();
    $permission3 = Permission::factory()->forTenant($this->tenant)->create();

    // Give initial permissions
    $role->givePermissionTo([$permission1, $permission2]);

    // Sync to only permission3
    $role->syncPermissions([$permission3]);

    expect($role->permissions)->toHaveCount(1);
    expect($role->hasPermissionTo($permission1))->toBeFalse();
    expect($role->hasPermissionTo($permission2))->toBeFalse();
    expect($role->hasPermissionTo($permission3))->toBeTrue();
});

test('role has correct fillable attributes', function (): void {
    $role = new Role();

    expect($role->getFillable())->toBe([
        'name',
        'guard_name',
    ]);
});

test('role has correct casts', function (): void {
    $role = new Role();

    expect($role->getCasts())->toHaveKey('id');
    expect($role->getCasts())->toHaveKey('tenant_id');
    expect($role->getCasts())->toHaveKey('name');
    expect($role->getCasts())->toHaveKey('guard_name');
});

test('role has correct hidden attributes', function (): void {
    $role = new Role();

    expect($role->getHidden())->toContain('tenant_id');
});

test('role observer deletes permissions when role is deleted', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    $role->givePermissionTo($permission);
    expect($role->permissions)->toHaveCount(1);

    // Delete the role
    $role->delete();

    // The observer deletes the permissions when the role is deleted
    expect(Permission::count())->toBe(0);
    expect($role->permissions()->count())->toBe(0);
});

test('role can be found by name', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create([
        'name'       => 'super-admin',
        'guard_name' => 'web',
    ]);

    $foundRole = Role::where('name', 'super-admin')->first();

    expect($foundRole)->not->toBeNull();
    expect($foundRole->id)->toBe($role->id);
    expect($foundRole->name)->toBe('super-admin');
});

test('role can be found by guard name', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create([
        'name'       => 'admin',
        'guard_name' => 'api',
    ]);

    $foundRole = Role::where('guard_name', 'api')->first();

    expect($foundRole)->not->toBeNull();
    expect($foundRole->id)->toBe($role->id);
    expect($foundRole->guard_name)->toBe('api');
});

test('role has activity logging enabled', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();

    expect(method_exists($role, 'getActivitylogOptions'))->toBeTrue();

    $options = $role->getActivitylogOptions();
    expect($options)->toBeInstanceOf(Spatie\Activitylog\LogOptions::class);
});
