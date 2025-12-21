<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\User\Models\User;

/**
 * @extends Factory<NewsletterSubscriber>
 */
final class NewsletterSubscriberFactory extends Factory
{
    /**
     * @var class-string<NewsletterSubscriber>
     */
    protected $model = NewsletterSubscriber::class;

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
