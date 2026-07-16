<?php

declare(strict_types=1);

namespace Misaf\VendraDocument\Console\Commands;

use Misaf\VendraDocument\Database\Seeders\PermissionPolicySeeder;
use Misaf\VendraSupport\Console\Commands\TenantSeedCommand;

final class SeedCommand extends TenantSeedCommand
{
    protected const string MODULE_NAME = 'vendra-document';

    protected $signature = 'vendra-document:seed
        {tenant? : Tenant ID or slug to seed document permissions for}
        {seeders?* : Seeder keys to run. Use "all" or: permission-policies}';

    protected $description = 'Seed document module data for a tenant';

    /** @return array<string, class-string> */
    protected function seeders(): array
    {
        return ['permission-policies' => PermissionPolicySeeder::class];
    }
}
