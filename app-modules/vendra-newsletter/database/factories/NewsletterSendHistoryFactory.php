<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\VendraNewsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;

/**
 * @extends Factory<NewsletterSendHistory>
 */
#[UseModel(NewsletterSendHistory::class)]
final class NewsletterSendHistoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween('-2 hour', '-1 hour');

        return [
            'newsletter_id'     => Newsletter::factory(),
            'token'             => $this->faker->uuid(),
            'status'            => NewsletterSendHistoryStatusEnum::SENT,
            'total_subscribers' => 0,
            'sent_count'        => 0,
            'failed_count'      => 0,
            'started_at'        => $startedAt,
            'completed_at'      => $this->faker->dateTimeBetween($startedAt, 'now'),
        ];
    }

    public function withNewsletter(Newsletter $newsletter): static
    {
        return $this->state(fn(array $attributes): array => [
            'newsletter_id' => $newsletter->id,
        ]);
    }

    public function sending(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status' => NewsletterSendHistoryStatusEnum::SENDING,
        ]);
    }

    public function sent(): static
    {
        return $this->state(fn(array $attributes): array => [
            'sent_count' => $attributes['total_subscribers'],
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status'       => NewsletterSendHistoryStatusEnum::FAILED,
            'failed_count' => $attributes['total_subscribers'],
        ]);
    }
}
