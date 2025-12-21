<?php

declare(strict_types=1);

use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;

// Include helper functions
require_once __DIR__ . '/../Helpers/RoleAssignmentHelpers.php';

test('individual role naming pattern works correctly', function (): void {
    // Create users with different usernames and emails
    $user1 = User::factory()->forTenant($this->tenant)->create([
        'username' => 'john_doe',
        'email'    => 'john@example.com',
    ]);

    $user2 = User::factory()->forTenant($this->tenant)->create([
        'username' => '', // Empty username, should use email
        'email'    => 'jane.smith@example.com',
    ]);

    $user3 = User::factory()->forTenant($this->tenant)->create([
        'username' => 'admin-user',
        'email'    => 'admin@test.com',
    ]);

    // Assign individual roles to each user
    assignIndividualRoleToUser($user1);
    assignIndividualRoleToUser($user2);
    assignIndividualRoleToUser($user3);

    // Check that each user has exactly one role
    expect($user1->roles)->toHaveCount(1);
    expect($user2->roles)->toHaveCount(1);
    expect($user3->roles)->toHaveCount(1);

    // Check role naming patterns
    $role1Name = $user1->roles->first()->name;
    $role2Name = $user2->roles->first()->name;
    $role3Name = $user3->roles->first()->name;

    // User with username
    expect($role1Name)->toBe("user_{$user1->id}_john_doe");

    // User without username (should use email)
    expect($role2Name)->toBe("user_{$user2->id}_jane_smith_example_com");

    // User with hyphenated username
    expect($role3Name)->toBe("user_{$user3->id}_admin_user");

    // Verify all roles are unique
    expect($role1Name)->not->toBe($role2Name);
    expect($role1Name)->not->toBe($role3Name);
    expect($role2Name)->not->toBe($role3Name);
});

test('individual roles are unique per user', function (): void {
    // Create multiple users in the same tenant
    $user1 = User::factory()->forTenant($this->tenant)->create([
        'username' => 'user1',
        'email'    => 'user1@example.com',
    ]);

    $user2 = User::factory()->forTenant($this->tenant)->create([
        'username' => 'user2',
        'email'    => 'user2@example.com',
    ]);

    $user3 = User::factory()->forTenant($this->tenant)->create([
        'username' => 'user3',
        'email'    => 'user3@example.com',
    ]);

    // Assign individual roles to each user
    assignIndividualRoleToUser($user1);
    assignIndividualRoleToUser($user2);
    assignIndividualRoleToUser($user3);

    // Check that each user has exactly one role
    expect($user1->roles)->toHaveCount(1);
    expect($user2->roles)->toHaveCount(1);
    expect($user3->roles)->toHaveCount(1);

    // Check role names
    $role1Name = $user1->roles->first()->name;
    $role2Name = $user2->roles->first()->name;
    $role3Name = $user3->roles->first()->name;

    expect($role1Name)->toBe("user_{$user1->id}_user1");
    expect($role2Name)->toBe("user_{$user2->id}_user2");
    expect($role3Name)->toBe("user_{$user3->id}_user3");

    // Verify all roles are unique
    expect($role1Name)->not->toBe($role2Name);
    expect($role1Name)->not->toBe($role3Name);
    expect($role2Name)->not->toBe($role3Name);

    // Verify all roles belong to the same tenant
    expect($user1->roles->first()->tenant_id)->toBe($this->tenant->id);
    expect($user2->roles->first()->tenant_id)->toBe($this->tenant->id);
    expect($user3->roles->first()->tenant_id)->toBe($this->tenant->id);
});
