<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            NewsletterSeeder::class,
        ]);
    }
}
