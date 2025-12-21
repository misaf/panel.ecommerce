<?php

declare(strict_types=1);

namespace Misaf\Permission\Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Misaf\Permission\Models\Role;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;

final class RoleAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Get all tenants
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            throw new Exception('No tenants found. Please run the tenant seeder first.');
        }

        if ($this->command) {
            $this->command->info('Starting role assignment for all tenants...');
        }

        // Process each tenant
        foreach ($tenants as $tenant) {
            $this->assignRolesForTenant($tenant);
        }

        if ($this->command) {
            $this->command->info('Role assignment completed successfully!');
        }
    }

    /**
     * Assign roles for a specific tenant.
     *
     * @param Tenant $tenant
     * @return void
     */
    private function assignRolesForTenant(Tenant $tenant): void
    {
        if ($this->command) {
            $this->command->info("Processing tenant: {$tenant->name}");
        }

        try {
            // Get users for this tenant (without global scope to get all users for this tenant)
            $users = User::withoutGlobalScope(\Misaf\Tenant\Scopes\TenantScope::class)
                ->where('tenant_id', $tenant->id)
                ->get();

            if ($this->command) {
                $this->command->info("Found users for tenant {$tenant->name}: " . $users->map(fn($u) => $u->email)->implode(', '));
            }

            if ($users->isEmpty()) {
                if ($this->command) {
                    $this->command->info("No users found for tenant: {$tenant->name}. Skipping...");
                }
                return;
            }

            if ($this->command) {
                $this->command->info("Found {$users->count()} users for tenant: {$tenant->name}");
            }

            // Assign individual roles to each user
            foreach ($users as $user) {
                $this->assignRoleToUser($user);
            }

            if ($this->command) {
                $this->command->info("Completed role assignment for tenant: {$tenant->name}");
            }

        } catch (Exception $e) {
            if ($this->command) {
                $this->command->error("Failed to assign roles for tenant {$tenant->name}: " . $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Assign individual role to a specific user.
     *
     * @param User $user
     * @return void
     */
    private function assignRoleToUser(User $user): void
    {
        // Skip if user already has roles
        if ($user->roles()->count() > 0) {
            if ($this->command) {
                $this->command->line("User {$user->email} already has roles. Skipping...");
            }
            return;
        }

        // Create individual role for this user
        $roleName = $this->generateUserRoleName($user);
        $role = $this->createUserRole($user, $roleName);

        if ($role) {
            $user->assignRole($role);
            if ($this->command) {
                $this->command->line("Created and assigned role '{$roleName}' to user {$user->email}");
            }
        } else {
            if ($this->command) {
                $this->command->warn("Failed to create role for user {$user->email}");
            }
        }
    }

    /**
     * Generate a unique role name for a user.
     *
     * @param User $user
     * @return string
     */
    private function generateUserRoleName(User $user): string
    {
        $baseName = ! empty($user->username) ? $user->username : $user->email;
        $baseName = preg_replace('/[^a-zA-Z0-9]/', '_', $baseName);
        $baseName = mb_strtolower($baseName);

        return "user_{$user->id}_{$baseName}";
    }

    /**
     * Create a role for a specific user.
     *
     * @param User $user
     * @param string $roleName
     * @return Role|null
     */
    private function createUserRole(User $user, string $roleName): ?Role
    {
        $user->loadMissing('tenant', 'roles');

        // Check if role already exists (without global scope to check across all tenants)
        $existingRole = Role::withoutGlobalScope(\Misaf\Tenant\Scopes\TenantScope::class)
            ->where('tenant_id', $user->tenant_id)
            ->where('name', $roleName)
            ->where('guard_name', 'web')
            ->first();

        if ($existingRole) {
            return $existingRole;
        }

        // Create new role for the user (without global scope to avoid tenant filtering)
        $role = Role::withoutGlobalScope(\Misaf\Tenant\Scopes\TenantScope::class)->create([
            'tenant_id'  => $user->tenant_id,
            'name'       => $roleName,
            'guard_name' => 'web',
        ]);

        return $role;
    }
}
