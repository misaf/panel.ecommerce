<?php

declare(strict_types=1);

namespace Misaf\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\User\Models\User;
use Misaf\User\Models\UserProfile;

/**
 * @extends Factory<UserProfile>
 */
final class UserProfileFactory extends Factory
{
    /**
     * @var class-string<UserProfile>
     */
    protected $model = UserProfile::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'first_name'  => fake()->firstName(),
            'last_name'   => fake()->lastName(),
            'description' => fake()->text(),
            'birthdate'   => null,
            'status'      => fake()->boolean(),
        ];
    }
}
