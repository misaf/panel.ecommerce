<?php

declare(strict_types=1);

namespace Misaf\VendraPhone\Console\Commands;

use Misaf\VendraPhone\Database\Seeders\PermissionPolicySeeder;
use Misaf\VendraSupport\Console\Commands\TenantSeedCommand;

final class SeedCommand extends TenantSeedCommand
{
    protected const string MODULE_NAME = 'vendra-phone';

    protected $signature = 'vendra-phone:seed
        {tenant? : Tenant ID or slug to seed phone permissions for}
        {seeders?* : Seeder keys to run. Use "all" or: permission-policies}';

    protected $description = 'Seed phone module data for a tenant';

    /** @return array<string, class-string> */
    protected function seeders(): array
    {
        return ['permission-policies' => PermissionPolicySeeder::class];
    }
}
