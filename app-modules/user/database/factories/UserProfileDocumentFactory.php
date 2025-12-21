<?php

declare(strict_types=1);

namespace Misaf\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\User\Enums\UserProfileDocumentStatusEnum;
use Misaf\User\Models\UserProfile;
use Misaf\User\Models\UserProfileDocument;

/**
 * @extends Factory<UserProfileDocument>
 */
final class UserProfileDocumentFactory extends Factory
{
    /**
     * @var class-string<UserProfileDocument>
     */
    protected $model = UserProfileDocument::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_profile_id' => UserProfile::factory(),
            'status'          => fake()->randomElement(UserProfileDocumentStatusEnum::cases()),
            'verified_at'     => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
