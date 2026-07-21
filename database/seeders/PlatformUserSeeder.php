<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PlatformUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class PlatformUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = config('platform.operator.username');
        $email = config('platform.operator.email');
        $password = config('platform.operator.password');

        if (blank($email) && blank($password)) {
            return;
        }

        if ( ! is_string($username) || blank($username)) {
            throw new InvalidArgumentException('PLATFORM_OPERATOR_USERNAME must be a non-empty string.');
        }

        if ( ! is_string($email) || false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('PLATFORM_OPERATOR_EMAIL must be a valid email address.');
        }

        if ( ! is_string($password) || mb_strlen($password) < 12) {
            throw new InvalidArgumentException('PLATFORM_OPERATOR_PASSWORD must contain at least 12 characters.');
        }

        PlatformUser::query()->firstOrCreate(
            ['email' => Str::lower(mb_trim($email))],
            [
                'username'          => $username,
                'email_verified_at' => now(),
                'password'          => $password,
            ],
        );
    }
}
