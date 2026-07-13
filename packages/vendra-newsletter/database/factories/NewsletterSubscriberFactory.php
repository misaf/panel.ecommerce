<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraUser\Models\User;

/**
 * @extends Factory<NewsletterSubscriber>
 */
#[UseModel(NewsletterSubscriber::class)]
final class NewsletterSubscriberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->optional(0.7)->randomElement([User::factory(), null]),
            'email'   => $this->faker->unique()->safeEmail(),
        ];
    }

    public function withUser(User $user): static
    {
        return $this->state(fn(array $attributes): array => [
            'user_id' => $user->id,
        ]);
    }

    public function withoutUser(): static
    {
        return $this->state(fn(array $attributes): array => [
            'user_id' => null,
        ]);
    }
}
