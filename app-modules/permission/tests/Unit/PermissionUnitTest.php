<?php

declare(strict_types=1);

use Misaf\Permission\Models\Permission;

test('permission model extends spatie permission', function (): void {
    $permission = new Permission();

    expect($permission)->toBeInstanceOf(Spatie\Permission\Models\Permission::class);
});

test('permission model uses required traits', function (): void {
    $permission = new Permission();

    expect(class_uses($permission))->toContain(Misaf\Tenant\Traits\BelongsToTenant::class);
    expect(class_uses($permission))->toContain(Illuminate\Database\Eloquent\Factories\HasFactory::class);
    expect(class_uses($permission))->toContain(Spatie\Activitylog\Traits\LogsActivity::class);
    expect(class_uses($permission))->toContain(Illuminate\Database\Eloquent\SoftDeletes::class);
});

test('permission model has correct table name', function (): void {
    $permission = new Permission();

    expect($permission->getTable())->toBe('permissions');
});

test('permission model has correct primary key', function (): void {
    $permission = new Permission();

    expect($permission->getKeyName())->toBe('id');
});

test('permission model has timestamps enabled', function (): void {
    $permission = new Permission();

    expect($permission->usesTimestamps())->toBeTrue();
});

test('permission model has soft deletes enabled', function (): void {
    $permission = new Permission();

    expect(in_array(Illuminate\Database\Eloquent\SoftDeletes::class, class_uses($permission)))->toBeTrue();
});

test('permission model has activity logging enabled', function (): void {
    $permission = new Permission();

    expect(in_array(Spatie\Activitylog\Traits\LogsActivity::class, class_uses($permission)))->toBeTrue();
});

test('permission model has correct activity log options', function (): void {
    $permission = new Permission();

    $options = $permission->getActivitylogOptions();

    expect($options)->toBeInstanceOf(Spatie\Activitylog\LogOptions::class);
});

test('permission model has correct factory', function (): void {
    $permission = new Permission();

    $reflection = new ReflectionClass(Permission::class);
    $method = $reflection->getMethod('newFactory');
    $method->setAccessible(true);

    $factory = $method->invoke(null);
    expect($factory)->toBeInstanceOf(Misaf\Permission\Database\Factories\PermissionFactory::class);
});

test('permission model has correct observer', function (): void {
    $reflection = new ReflectionClass(Permission::class);
    $attributes = $reflection->getAttributes(Illuminate\Database\Eloquent\Attributes\ObservedBy::class);

    expect($attributes)->toHaveCount(1);

    $attribute = $attributes[0];
    $arguments = $attribute->getArguments();

    expect($arguments[0])->toContain(Misaf\Permission\Observers\PermissionObserver::class);
});

test('permission model has correct fillable attributes', function (): void {
    $permission = new Permission();

    expect($permission->getFillable())->toBe([
        'name',
        'guard_name',
    ]);
});

test('permission model has correct casts', function (): void {
    $permission = new Permission();

    expect($permission->getCasts())->toHaveKey('id');
    expect($permission->getCasts())->toHaveKey('tenant_id');
    expect($permission->getCasts())->toHaveKey('name');
    expect($permission->getCasts())->toHaveKey('guard_name');
});

test('permission model has correct hidden attributes', function (): void {
    $permission = new Permission();

    expect($permission->getHidden())->toContain('tenant_id');
});

test('permission model has correct activity log configuration', function (): void {
    $permission = new Permission();

    $options = $permission->getActivitylogOptions();

    // Test that the options are configured correctly
    expect($options)->toBeInstanceOf(Spatie\Activitylog\LogOptions::class);
});

test('permission model is observed by permission observer', function (): void {
    $reflection = new ReflectionClass(Permission::class);
    $attributes = $reflection->getAttributes(Illuminate\Database\Eloquent\Attributes\ObservedBy::class);

    expect($attributes)->toHaveCount(1);

    $attribute = $attributes[0];
    $arguments = $attribute->getArguments();

    expect($arguments[0])->toContain(Misaf\Permission\Observers\PermissionObserver::class);
});
