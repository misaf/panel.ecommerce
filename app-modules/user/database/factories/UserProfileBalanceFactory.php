<?php

declare(strict_types=1);

namespace Misaf\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Currency\Models\Currency;
use Misaf\User\Models\UserProfile;
use Misaf\User\Models\UserProfileBalance;

/**
 * @extends Factory<UserProfileBalance>
 */
final class UserProfileBalanceFactory extends Factory
{
    /**
     * @var class-string<UserProfileBalance>
     */
    protected $model = UserProfileBalance::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_profile_id' => UserProfile::factory(),
            'currency_id'     => Currency::factory(),
            'status'          => fake()->boolean(),
        ];
    }
}
