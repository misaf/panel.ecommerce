<?php

declare(strict_types=1);

namespace Misaf\VendraAddress\Console\Commands;

use Misaf\VendraAddress\Database\Seeders\PermissionPolicySeeder;
use Misaf\VendraSupport\Tenancy\Console\Commands\TenantSeedCommand;

final class SeedCommand extends TenantSeedCommand
{
    protected const string MODULE_NAME = 'vendra-address';

    protected $signature = 'vendra-address:seed
        {tenant? : Tenant ID or slug to seed address permissions for}
        {seeders?* : Seeder keys to run. Use "all" or: permission-policies}';

    protected $description = 'Seed address module data for a tenant';

    /** @return array<string, class-string> */
    protected function seeders(): array
    {
        return ['permission-policies' => PermissionPolicySeeder::class];
    }
}
