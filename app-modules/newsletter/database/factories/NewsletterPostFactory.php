<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Newsletter\Enums\NewsletterPostStatusEnum;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;

/**
 * @extends Factory<NewsletterPost>
 */
final class NewsletterPostFactory extends Factory
{
    /**
     * @var class-string<NewsletterPost>
     */
    protected $model = NewsletterPost::class;

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
