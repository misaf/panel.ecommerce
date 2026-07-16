<?php

declare(strict_types=1);

namespace Misaf\VendraVerification\Console\Commands;

use Misaf\VendraSupport\Console\Commands\TenantSeedCommand;
use Misaf\VendraVerification\Database\Seeders\PermissionPolicySeeder;

final class SeedCommand extends TenantSeedCommand
{
    protected const string MODULE_NAME = 'vendra-verification';

    protected $signature = 'vendra-verification:seed
        {tenant? : Tenant ID or slug to seed verification permissions for}
        {seeders?* : Seeder keys to run. Use "all" or: permission-policies}';

    protected $description = 'Seed verification module data for a tenant';

    /** @return array<string, class-string> */
    protected function seeders(): array
    {
        return ['permission-policies' => PermissionPolicySeeder::class];
    }
}
