<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\VendraNewsletter\Enums\NewsletterPostStatusEnum;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterPost;

/**
 * @extends Factory<NewsletterPost>
 */
#[UseModel(NewsletterPost::class)]
final class NewsletterPostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate newsletter creation time first, then newsletter post creation time after it
        $newsletterCreatedAt = $this->faker->dateTimeBetween('-1 month', '-1 day');

        return [
            'newsletter_id' => Newsletter::factory()->state(['created_at' => $newsletterCreatedAt]),
            'name'          => [
                'en' => $this->faker->sentence(4),
                'fa' => $this->faker->sentence(4),
            ],
            'description' => [
                'en' => $this->faker->paragraphs(3, true),
                'fa' => $this->faker->paragraphs(3, true),
            ],
            'status' => $this->faker->randomElement(NewsletterPostStatusEnum::cases()),
        ];
    }

    public function withNewsletter(Newsletter $newsletter): static
    {
        return $this->state(fn(array $attributes): array => [
            'newsletter_id' => $newsletter->id,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status' => NewsletterPostStatusEnum::DRAFT,
        ]);
    }

    public function ready(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status' => NewsletterPostStatusEnum::READY,
        ]);
    }
}
