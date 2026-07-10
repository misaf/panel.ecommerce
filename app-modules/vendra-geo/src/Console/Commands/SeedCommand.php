<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Console\Commands;

use Misaf\VendraGeo\Database\Seeders\PermissionPolicySeeder;
use Misaf\VendraGeo\GeoPlugin;
use Misaf\VendraSupport\Console\Commands\TenantSeedCommand;

final class SeedCommand extends TenantSeedCommand
{
    protected const string MODULE_NAME = GeoPlugin::ID;

    protected $signature = self::MODULE_NAME . ':seed
        {tenant? : Tenant ID or slug to seed geo data for}
        {seeders?* : Seeder keys to run. Use "all" or one or more of: permission-policies}';

    protected $description = 'Seed geo module data for a tenant';

    /**
     * @return array<string, class-string>
     */
    protected function seeders(): array
    {
        return [
            'permission-policies' => PermissionPolicySeeder::class,
        ];
    }
}
