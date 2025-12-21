<?php

declare(strict_types=1);

namespace Misaf\Permission\Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Misaf\Permission\Models\Permission;
use Misaf\Permission\Models\Role;
use Misaf\Tenant\Models\Tenant;

final class PermissionSeeder extends Seeder
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

        // Process each tenant
        foreach ($tenants as $tenant) {
            $this->seedForTenant($tenant);
        }
    }

    /**
     * Seed permissions and roles for a specific tenant.
     *
     * @param Tenant $tenant
     * @return void
     */
    private function seedForTenant(Tenant $tenant): void
    {
        if ($this->command) {
            $this->command->info("Processing tenant: {$tenant->name}");
        }

        try {
            // Create basic permissions
            $permissions = $this->createPermissions($tenant);

            // Create basic roles
            $roles = $this->createRoles($tenant);

            // Assign permissions to roles
            $this->assignPermissionsToRoles($roles, $permissions);

            if ($this->command) {
                $this->command->info("Completed seeding for tenant: {$tenant->name}");
            }
        } catch (Exception $e) {
            if ($this->command) {
                $this->command->error("Failed to seed tenant {$tenant->name}: " . $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Create basic permissions for the tenant.
     *
     * @param Tenant $tenant
     * @return array<string, Permission>
     */
    private function createPermissions(Tenant $tenant): array
    {
        $permissions = [
            // User permissions
            'view-users'             => 'View users',
            'view-any-users'         => 'View any users',
            'create-users'           => 'Create users',
            'update-users'           => 'Update users',
            'delete-users'           => 'Delete users',
            'delete-any-users'       => 'Delete any users',
            'force-delete-users'     => 'Force delete users',
            'force-delete-any-users' => 'Force delete any users',
            'restore-users'          => 'Restore users',
            'restore-any-users'      => 'Restore any users',
            'replicate-users'        => 'Replicate users',

            // Role permissions
            'view-roles'             => 'View roles',
            'view-any-roles'         => 'View any roles',
            'create-roles'           => 'Create roles',
            'update-roles'           => 'Update roles',
            'delete-roles'           => 'Delete roles',
            'delete-any-roles'       => 'Delete any roles',
            'force-delete-roles'     => 'Force delete roles',
            'force-delete-any-roles' => 'Force delete any roles',
            'restore-roles'          => 'Restore roles',
            'restore-any-roles'      => 'Restore any roles',
            'replicate-roles'        => 'Replicate roles',

            // Permission permissions
            'view-permissions'             => 'View permissions',
            'view-any-permissions'         => 'View any permissions',
            'create-permissions'           => 'Create permissions',
            'update-permissions'           => 'Update permissions',
            'delete-permissions'           => 'Delete permissions',
            'delete-any-permissions'       => 'Delete any permissions',
            'force-delete-permissions'     => 'Force delete permissions',
            'force-delete-any-permissions' => 'Force delete any permissions',
            'restore-permissions'          => 'Restore permissions',
            'restore-any-permissions'      => 'Restore any permissions',
            'replicate-permissions'        => 'Replicate permissions',

            // Tenant permissions
            'view-tenants'             => 'View tenants',
            'view-any-tenants'         => 'View any tenants',
            'create-tenants'           => 'Create tenants',
            'update-tenants'           => 'Update tenants',
            'delete-tenants'           => 'Delete tenants',
            'delete-any-tenants'       => 'Delete any tenants',
            'force-delete-tenants'     => 'Force delete tenants',
            'force-delete-any-tenants' => 'Force delete any tenants',
            'restore-tenants'          => 'Restore tenants',
            'restore-any-tenants'      => 'Restore any tenants',
            'replicate-tenants'        => 'Replicate tenants',

            // System permissions
            'access-admin-panel'     => 'Access admin panel',
            'manage-system-settings' => 'Manage system settings',
            'view-system-logs'       => 'View system logs',
            'manage-backups'         => 'Manage backups',
        ];

        $createdPermissions = [];

        foreach ($permissions as $name => $displayName) {
            $createdPermissions[$name] = Permission::firstOrCreate([
                'name'       => $name,
                'guard_name' => 'web',
                'tenant_id'  => $tenant->id,
            ]);
        }

        return $createdPermissions;
    }

    /**
     * Create basic roles for the tenant.
     *
     * @param Tenant $tenant
     * @return array<string, Role>
     */
    private function createRoles(Tenant $tenant): array
    {
        $roles = [
            'super-admin' => 'Super Administrator',
            'admin'       => 'Administrator',
            'moderator'   => 'Moderator',
            'user'        => 'User',
        ];

        $createdRoles = [];

        foreach ($roles as $name => $displayName) {
            $createdRoles[$name] = Role::firstOrCreate([
                'name'       => $name,
                'guard_name' => 'web',
                'tenant_id'  => $tenant->id,
            ]);
        }

        return $createdRoles;
    }

    /**
     * Assign permissions to roles.
     *
     * @param array<string, Role> $roles
     * @param array<string, Permission> $permissions
     * @return void
     */
    private function assignPermissionsToRoles(array $roles, array $permissions): void
    {
        // Super Admin gets all permissions
        $roles['super-admin']->givePermissionTo(array_values($permissions));

        // Admin gets most permissions except super admin specific ones
        $adminPermissions = array_filter($permissions, function ($key) {
            return ! in_array($key, [
                'manage-system-settings',
                'view-system-logs',
                'manage-backups',
            ]);
        }, ARRAY_FILTER_USE_KEY);
        $roles['admin']->givePermissionTo(array_values($adminPermissions));

        // Moderator gets limited permissions
        $moderatorPermissions = array_filter($permissions, function ($key) {
            return in_array($key, [
                'view-users',
                'view-any-users',
                'update-users',
                'view-roles',
                'view-any-roles',
                'view-permissions',
                'view-any-permissions',
                'view-tenants',
                'view-any-tenants',
                'access-admin-panel',
            ]);
        }, ARRAY_FILTER_USE_KEY);
        $roles['moderator']->givePermissionTo(array_values($moderatorPermissions));

        // User gets basic permissions
        $userPermissions = array_filter($permissions, function ($key) {
            return in_array($key, [
                'view-users',
                'view-roles',
                'view-permissions',
                'view-tenants',
            ]);
        }, ARRAY_FILTER_USE_KEY);
        $roles['user']->givePermissionTo(array_values($userPermissions));
    }
}
