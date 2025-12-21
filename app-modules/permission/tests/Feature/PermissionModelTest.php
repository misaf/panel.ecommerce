<?php

declare(strict_types=1);

use Misaf\Permission\Models\Permission;
use Misaf\Permission\Models\Role;

test('permission can be created with factory', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    expect($permission)->toBeInstanceOf(Permission::class);
    expect($permission->name)->toBeString();
    expect($permission->guard_name)->toBeString();
    expect($permission->tenant_id)->toBe($this->tenant->id);
});

test('permission can be created manually', function (): void {
    $permission = Permission::create([
        'name'       => 'test-permission',
        'guard_name' => 'web',
        'tenant_id'  => $this->tenant->id,
    ]);

    expect($permission->name)->toBe('test-permission');
    expect($permission->guard_name)->toBe('web');
    expect($permission->id)->toBeInt();
    expect($permission->tenant_id)->toBe($this->tenant->id);
});

test('permission belongs to tenant', function (): void {
    $permission = Permission::create([
        'name'       => 'test-permission',
        'guard_name' => 'web',
        'tenant_id'  => $this->tenant->id,
    ]);

    expect($permission->tenant_id)->toBe($this->tenant->id);
});

test('permission can be soft deleted', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    expect(Permission::count())->toBe(1);

    $permission->delete();

    expect(Permission::count())->toBe(0);
    expect(Permission::withTrashed()->count())->toBe(1);
    expect($permission->trashed())->toBeTrue();
});

test('permission can be restored after soft delete', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    $permission->delete();
    expect(Permission::count())->toBe(0);

    $permission->restore();
    expect(Permission::count())->toBe(1);
    expect($permission->trashed())->toBeFalse();
});

test('permission can be force deleted', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    expect(Permission::count())->toBe(1);

    $permission->forceDelete();

    expect(Permission::count())->toBe(0);
    expect(Permission::withTrashed()->count())->toBe(0);
});

test('permission can be assigned to roles', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();
    $role = Role::factory()->forTenant($this->tenant)->create();

    $role->givePermissionTo($permission);

    expect($role->hasPermissionTo($permission))->toBeTrue();
    expect($permission->roles)->toHaveCount(1);
    expect($permission->roles->first()->id)->toBe($role->id);
});

test('permission can be assigned to multiple roles', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();
    $role1 = Role::factory()->forTenant($this->tenant)->create();
    $role2 = Role::factory()->forTenant($this->tenant)->create();
    $role3 = Role::factory()->forTenant($this->tenant)->create();

    $permission->assignRole([$role1, $role2, $role3]);

    expect($permission->roles)->toHaveCount(3);
    expect($role1->hasPermissionTo($permission))->toBeTrue();
    expect($role2->hasPermissionTo($permission))->toBeTrue();
    expect($role3->hasPermissionTo($permission))->toBeTrue();
});

test('permission can be removed from roles', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();
    $role = Role::factory()->forTenant($this->tenant)->create();

    $role->givePermissionTo($permission);
    expect($role->hasPermissionTo($permission))->toBeTrue();

    $role->revokePermissionTo($permission);
    expect($role->hasPermissionTo($permission))->toBeFalse();
    expect($permission->roles)->toHaveCount(0);
});

test('permission can sync roles', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();
    $role1 = Role::factory()->forTenant($this->tenant)->create();
    $role2 = Role::factory()->forTenant($this->tenant)->create();
    $role3 = Role::factory()->forTenant($this->tenant)->create();

    // Assign initial roles
    $permission->assignRole([$role1, $role2]);

    // Sync to only role3
    $permission->syncRoles([$role3]);

    expect($permission->roles)->toHaveCount(1);
    expect($role1->hasPermissionTo($permission))->toBeFalse();
    expect($role2->hasPermissionTo($permission))->toBeFalse();
    expect($role3->hasPermissionTo($permission))->toBeTrue();
});

test('permission has correct fillable attributes', function (): void {
    $permission = new Permission();

    expect($permission->getFillable())->toBe([
        'name',
        'guard_name',
    ]);
});

test('permission has correct casts', function (): void {
    $permission = new Permission();

    expect($permission->getCasts())->toHaveKey('id');
    expect($permission->getCasts())->toHaveKey('tenant_id');
    expect($permission->getCasts())->toHaveKey('name');
    expect($permission->getCasts())->toHaveKey('guard_name');
});

test('permission has correct hidden attributes', function (): void {
    $permission = new Permission();

    expect($permission->getHidden())->toContain('tenant_id');
});

test('permission can be found by name', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'name'       => 'test-permission',
        'guard_name' => 'web',
    ]);

    $foundPermission = Permission::where('name', 'test-permission')->first();

    expect($foundPermission)->not->toBeNull();
    expect($foundPermission->id)->toBe($permission->id);
    expect($foundPermission->name)->toBe('test-permission');
});

test('permission can be found by guard name', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'name'       => 'test-permission',
        'guard_name' => 'api',
    ]);

    $foundPermission = Permission::where('guard_name', 'api')->first();

    expect($foundPermission)->not->toBeNull();
    expect($foundPermission->id)->toBe($permission->id);
    expect($foundPermission->guard_name)->toBe('api');
});

test('permission has activity logging enabled', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    expect(method_exists($permission, 'getActivitylogOptions'))->toBeTrue();

    $options = $permission->getActivitylogOptions();
    expect($options)->toBeInstanceOf(Spatie\Activitylog\LogOptions::class);
});

test('permission can be found by exact name', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'name'       => 'specific-permission',
        'guard_name' => 'web',
    ]);

    $foundPermission = Permission::findByName('specific-permission');

    expect($foundPermission)->not->toBeNull();
    expect($foundPermission->id)->toBe($permission->id);
    expect($foundPermission->name)->toBe('specific-permission');
});

test('permission can be found by exact name and guard', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'name'       => 'specific-permission',
        'guard_name' => 'api',
    ]);

    $foundPermission = Permission::findByName('specific-permission', 'api');

    expect($foundPermission)->not->toBeNull();
    expect($foundPermission->id)->toBe($permission->id);
    expect($foundPermission->name)->toBe('specific-permission');
    expect($foundPermission->guard_name)->toBe('api');
});

test('permission can be found or created by name', function (): void {
    $permission = Permission::findOrCreate('new-permission');

    expect($permission)->toBeInstanceOf(Permission::class);
    expect($permission->name)->toBe('new-permission');
    expect($permission->guard_name)->toBe('web'); // default guard
    expect($permission->tenant_id)->toBe($this->tenant->id);
});

test('permission can be found or created by name and guard', function (): void {
    $permission = Permission::findOrCreate('new-permission', 'api');

    expect($permission)->toBeInstanceOf(Permission::class);
    expect($permission->name)->toBe('new-permission');
    expect($permission->guard_name)->toBe('api');
    expect($permission->tenant_id)->toBe($this->tenant->id);
});
