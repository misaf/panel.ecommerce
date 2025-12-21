<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Misaf\Currency\Database\Seeders\CurrencySeeder;
use Misaf\Newsletter\Database\Seeders\NewsletterSeeder;
use Misaf\Permission\Database\Seeders\PermissionSeeder;
use Misaf\Permission\Database\Seeders\RoleAssignmentSeeder;
use Misaf\Tenant\Database\Seeders\TenantSeeder;
use Misaf\User\Database\Seeders\UserSeeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * @return void
     */
    public function run(): void
    {
        $this->call([
            TenantSeeder::class,
            CurrencySeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            RoleAssignmentSeeder::class,
            SettingsSeeder::class,
            // NewsletterSeeder::class,
        ]);
    }
}
