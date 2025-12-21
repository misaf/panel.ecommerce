<?php

declare(strict_types=1);

use Misaf\Permission\Models\Role;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;

// Include helper functions
require_once __DIR__ . '/../Helpers/RoleAssignmentHelpers.php';

test('can assign individual roles to users for a tenant', function (): void {
    // Create users for the tenant
    $user1 = User::factory()->forTenant($this->tenant)->create([
        'username' => 'user1',
        'email'    => 'user1@example.com',
    ]);
    $user2 = User::factory()->forTenant($this->tenant)->create([
        'username' => 'user2',
        'email'    => 'user2@example.com',
    ]);

    // Assign individual roles to each user
    assignIndividualRoleToUser($user1);
    assignIndividualRoleToUser($user2);

    // Check that each user has exactly one role
    expect($user1->roles)->toHaveCount(1);
    expect($user2->roles)->toHaveCount(1);

    // Check role naming patterns
    $role1Name = $user1->roles->first()->name;
    $role2Name = $user2->roles->first()->name;

    expect($role1Name)->toBe("user_{$user1->id}_user1");
    expect($role2Name)->toBe("user_{$user2->id}_user2");

    // Verify roles are unique
    expect($role1Name)->not->toBe($role2Name);
});

test('can assign individual role to a specific user', function (): void {
    // Create a user
    $user = User::factory()->forTenant($this->tenant)->create([
        'username' => 'testuser',
        'email'    => 'test@example.com',
    ]);

    // Assign individual role to the user
    assignIndividualRoleToUser($user);

    // Check that user has exactly one role
    expect($user->roles)->toHaveCount(1);

    // Check role naming pattern
    $roleName = $user->roles->first()->name;
    expect($roleName)->toBe("user_{$user->id}_testuser");

    // Verify role belongs to the correct tenant
    expect($user->roles->first()->tenant_id)->toBe($this->tenant->id);
});

test('skips users who already have roles', function (): void {
    // Create a user
    $user = User::factory()->forTenant($this->tenant)->create([
        'username' => 'existinguser',
        'email'    => 'existing@example.com',
    ]);

    // Manually assign a role first
    $existingRole = Role::factory()->forTenant($this->tenant)->create([
        'name'       => 'existing-role',
        'guard_name' => 'web',
    ]);
    $user->assignRole($existingRole);

    // Try to assign individual role
    assignIndividualRoleToUser($user);

    // Check that user still has only the original role
    expect($user->roles)->toHaveCount(1);
    expect($user->roles->first()->name)->toBe('existing-role');
});

test('creates unique roles for users without username', function (): void {
    // Create a user without username
    $user = User::factory()->forTenant($this->tenant)->create([
        'username' => '',
        'email'    => 'no.username@example.com',
    ]);

    // Assign individual role to the user
    assignIndividualRoleToUser($user);

    // Check that user has exactly one role
    expect($user->roles)->toHaveCount(1);

    // Check role naming pattern (should use email)
    $roleName = $user->roles->first()->name;
    expect($roleName)->toBe("user_{$user->id}_no_username_example_com");
});

test('handles special characters in username', function (): void {
    // Create a user with special characters in username
    $user = User::factory()->forTenant($this->tenant)->create([
        'username' => 'user-name@123',
        'email'    => 'special@example.com',
    ]);

    // Assign individual role to the user
    assignIndividualRoleToUser($user);

    // Check that user has exactly one role
    expect($user->roles)->toHaveCount(1);

    // Check role naming pattern (special characters should be replaced with underscores)
    $roleName = $user->roles->first()->name;
    expect($roleName)->toBe("user_{$user->id}_user_name_123");
});
