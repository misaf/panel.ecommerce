<?php

declare(strict_types=1);

use Misaf\Permission\Database\Factories\RoleFactory;
use Misaf\Permission\Models\Role;

test('role factory can create a role', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create();

    expect($role)->toBeInstanceOf(Role::class);
    expect($role->name)->toBeString();
    expect($role->guard_name)->toBeString();
    expect($role->id)->toBeInt();
});

test('role factory can create multiple roles', function (): void {
    $roles = Role::factory()->forTenant($this->tenant)->count(3)->create();

    expect($roles)->toHaveCount(3);

    foreach ($roles as $role) {
        expect($role)->toBeInstanceOf(Role::class);
        expect($role->name)->toBeString();
        expect($role->guard_name)->toBeString();
    }
});

test('role factory generates unique names', function (): void {
    $roles = Role::factory()->forTenant($this->tenant)->count(5)->create();

    $names = $roles->pluck('name')->toArray();
    $uniqueNames = array_unique($names);

    expect($uniqueNames)->toHaveCount(5);
});

test('role factory can create role with specific attributes', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create([
        'name'       => 'custom-admin',
        'guard_name' => 'custom-guard',
    ]);

    expect($role->name)->toBe('custom-admin');
    expect($role->guard_name)->toBe('custom-guard');
});

test('role factory can make role without saving', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->make();

    expect($role)->toBeInstanceOf(Role::class);
    expect($role->name)->toBeString();
    expect($role->guard_name)->toBeString();
    expect($role->exists)->toBeFalse();
});

test('role factory can create role with specific guard name', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'api',
    ]);

    expect($role->guard_name)->toBe('api');
});

test('role factory definition returns correct structure', function (): void {
    $factory = new RoleFactory();
    $definition = $factory->definition();

    expect($definition)->toHaveKey('name');
    expect($definition)->toHaveKey('guard_name');
    expect($definition['name'])->toBeString();
    expect($definition['guard_name'])->toBeNull();
});

test('role factory can create role with web guard', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'web',
    ]);

    expect($role->guard_name)->toBe('web');
});

test('role factory can create role with api guard', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'api',
    ]);

    expect($role->guard_name)->toBe('api');
});

test('role factory can create role with custom guard', function (): void {
    $role = Role::factory()->forTenant($this->tenant)->create([
        'guard_name' => 'custom',
    ]);

    expect($role->guard_name)->toBe('custom');
});
