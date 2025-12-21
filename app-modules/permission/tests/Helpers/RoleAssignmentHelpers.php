<?php

declare(strict_types=1);

use Misaf\Permission\Models\Role;
use Misaf\User\Models\User;

/**
 * Helper function to assign individual role to a user.
 */
function assignIndividualRoleToUser(User $user): void
{
    // Skip if user already has roles
    if ($user->roles()->count() > 0) {
        return;
    }

    // Create individual role for this user
    $roleName = generateUserRoleName($user);
    $role = createUserRole($user, $roleName);

    if ($role) {
        $user->assignRole($role);
    }
}

/**
 * Generate a unique role name for a user.
 */
function generateUserRoleName(User $user): string
{
    $baseName = ! empty($user->username) ? $user->username : $user->email;
    $baseName = preg_replace('/[^a-zA-Z0-9]/', '_', $baseName);
    $baseName = mb_strtolower($baseName);

    return "user_{$user->id}_{$baseName}";
}

/**
 * Create a role for a specific user.
 */
function createUserRole(User $user, string $roleName): ?Role
{
    // Check if role already exists (without global scope to check across all tenants)
    $existingRole = Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)
        ->where('tenant_id', $user->tenant_id)
        ->where('name', $roleName)
        ->where('guard_name', 'web')
        ->first();

    if ($existingRole) {
        return $existingRole;
    }

    // Create new role for the user (without global scope to avoid tenant filtering)
    $role = Role::withoutGlobalScope(Misaf\Tenant\Scopes\TenantScope::class)->create([
        'tenant_id'  => $user->tenant_id,
        'name'       => $roleName,
        'guard_name' => 'web',
    ]);

    return $role;
}
