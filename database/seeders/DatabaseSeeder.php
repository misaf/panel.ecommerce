<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Misaf\VendraConsole\Database\Seeders\ConsoleUserSeeder;
use Misaf\VendraSubscription\Database\Seeders\PlanSeeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ConsoleUserSeeder::class,
            PlanSeeder::class,
        ]);
    }
}
