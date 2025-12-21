<?php

declare(strict_types=1);

use Misaf\Permission\Database\Factories\PermissionFactory;
use Misaf\Permission\Models\Permission;

test('permission factory can create a permission', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    expect($permission)->toBeInstanceOf(Permission::class);
    expect($permission->name)->toBeString();
    expect($permission->guard_name)->toBeString();
    expect($permission->id)->toBeInt();
});

test('permission factory can create multiple permissions', function (): void {
    $permissions = Permission::factory()->forTenant($this->tenant)->count(3)->create();

    expect($permissions)->toHaveCount(3);

    foreach ($permissions as $permission) {
        expect($permission)->toBeInstanceOf(Permission::class);
        expect($permission->name)->toBeString();
        expect($permission->guard_name)->toBeString();
    }
});

test('permission factory generates unique names', function (): void {
    $permissions = Permission::factory()->forTenant($this->tenant)->count(5)->create();

    $names = $permissions->pluck('name')->toArray();
    $uniqueNames = array_unique($names);

    expect($uniqueNames)->toHaveCount(5);
});

test('permission factory can create permission with specific attributes', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'name'       => 'custom-permission',
        'guard_name' => 'custom-guard',
    ]);

    expect($permission->name)->toBe('custom-permission');
    expect($permission->guard_name)->toBe('custom-guard');
});

test('permission factory can make permission without saving', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->make();

    expect($permission)->toBeInstanceOf(Permission::class);
    expect($permission->name)->toBeString();
    expect($permission->guard_name)->toBeString();
    expect($permission->exists)->toBeFalse();
});

test('permission factory can create permission with specific guard name', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'api',
    ]);

    expect($permission->guard_name)->toBe('api');
});

test('permission factory definition returns correct structure', function (): void {
    $factory = new PermissionFactory();
    $definition = $factory->definition();

    expect($definition)->toHaveKey('name');
    expect($definition)->toHaveKey('guard_name');
    expect($definition['name'])->toBeString();
    expect($definition['guard_name'])->toBeNull();
});

test('permission factory can create permission with web guard', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'web',
    ]);

    expect($permission->guard_name)->toBe('web');
});

test('permission factory can create permission with api guard', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'api',
    ]);

    expect($permission->guard_name)->toBe('api');
});

test('permission factory can create permission with custom guard', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'custom',
    ]);

    expect($permission->guard_name)->toBe('custom');
});

test('permission factory forTenant method sets tenant_id', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->create();

    expect($permission->tenant_id)->toBe($this->tenant->id);
});

test('permission factory forTenant method works with make', function (): void {
    $permission = Permission::factory()->forTenant($this->tenant)->make();

    expect($permission->tenant_id)->toBe($this->tenant->id);
});

test('permission factory forTenant method works with multiple permissions', function (): void {
    $permissions = Permission::factory()->forTenant($this->tenant)->count(3)->create();

    foreach ($permissions as $permission) {
        expect($permission->tenant_id)->toBe($this->tenant->id);
    }
});
