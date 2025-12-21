<?php

declare(strict_types=1);

use Misaf\Permission\Models\Role;

test('role model extends spatie role', function (): void {
    $role = new Role();

    expect($role)->toBeInstanceOf(Spatie\Permission\Models\Role::class);
});

test('role model uses required traits', function (): void {
    $role = new Role();

    expect(class_uses($role))->toContain(Misaf\Tenant\Traits\BelongsToTenant::class);
    expect(class_uses($role))->toContain(Illuminate\Database\Eloquent\Factories\HasFactory::class);
    expect(class_uses($role))->toContain(Spatie\Activitylog\Traits\LogsActivity::class);
    expect(class_uses($role))->toContain(Illuminate\Database\Eloquent\SoftDeletes::class);
});

test('role model has correct table name', function (): void {
    $role = new Role();

    expect($role->getTable())->toBe('roles');
});

test('role model has correct primary key', function (): void {
    $role = new Role();

    expect($role->getKeyName())->toBe('id');
});

test('role model has timestamps enabled', function (): void {
    $role = new Role();

    expect($role->usesTimestamps())->toBeTrue();
});

test('role model has soft deletes enabled', function (): void {
    $role = new Role();

    expect(in_array(Illuminate\Database\Eloquent\SoftDeletes::class, class_uses($role)))->toBeTrue();
});

test('role model has activity logging enabled', function (): void {
    $role = new Role();

    expect(in_array(Spatie\Activitylog\Traits\LogsActivity::class, class_uses($role)))->toBeTrue();
});

test('role model has correct activity log options', function (): void {
    $role = new Role();

    $options = $role->getActivitylogOptions();

    expect($options)->toBeInstanceOf(Spatie\Activitylog\LogOptions::class);
});

test('role model has correct factory', function (): void {
    $role = new Role();

    $reflection = new ReflectionClass(Role::class);
    $method = $reflection->getMethod('newFactory');
    $method->setAccessible(true);

    $factory = $method->invoke(null);
    expect($factory)->toBeInstanceOf(Misaf\Permission\Database\Factories\RoleFactory::class);
});

test('role model has correct observer', function (): void {
    $reflection = new ReflectionClass(Role::class);
    $attributes = $reflection->getAttributes(Illuminate\Database\Eloquent\Attributes\ObservedBy::class);

    expect($attributes)->toHaveCount(1);

    $attribute = $attributes[0];
    $arguments = $attribute->getArguments();

    expect($arguments[0])->toContain(Misaf\Permission\Observers\RoleObserver::class);
});
