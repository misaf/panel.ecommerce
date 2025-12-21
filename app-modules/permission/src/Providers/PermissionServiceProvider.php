<?php

declare(strict_types=1);

namespace Misaf\Permission\Providers;

use Illuminate\Support\ServiceProvider;

final class PermissionServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/permission.php', 'permission');
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/permission.php' => config_path('permission.php'),
        ], 'permission-config');
    }

    /**
     * Get package aliases.
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Role'       => \Misaf\Permission\Models\Role::class,
            'Permission' => \Misaf\Permission\Models\Permission::class,
        ];
    }
}
