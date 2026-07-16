<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Console\Commands;

use Misaf\VendraSupport\Console\Commands\TenantSeedCommand;
use Misaf\VendraUserProfile\Database\Seeders\PermissionPolicySeeder;

final class SeedCommand extends TenantSeedCommand
{
    protected const string MODULE_NAME = 'vendra-user-profile';

    protected $signature = 'vendra-user-profile:seed
        {tenant? : Tenant ID or slug to seed user profile permissions for}
        {seeders?* : Seeder keys to run. Use "all" or: permission-policies}';

    protected $description = 'Seed user profile module data for a tenant';

    /** @return array<string, class-string> */
    protected function seeders(): array
    {
        return ['permission-policies' => PermissionPolicySeeder::class];
    }
}
