<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ConsoleUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

final class ConsoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = Config::string('console.operator.username');
        $email = Config::string('console.operator.email');
        $password = Config::string('console.operator.password');

        if ('' === $username || '' === $email || '' === $password) {
            return;
        }

        ConsoleUser::query()->firstOrCreate(
            ['email' => Str::lower(mb_trim($email))],
            [
                'username'          => $username,
                'email_verified_at' => Carbon::now(),
                'password'          => $password,
            ],
        );
    }
}
