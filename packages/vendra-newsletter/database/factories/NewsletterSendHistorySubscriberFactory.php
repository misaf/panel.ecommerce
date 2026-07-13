<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\VendraNewsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;
use Misaf\VendraNewsletter\Models\NewsletterSendHistorySubscriber;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;

/**
 * @extends Factory<NewsletterSendHistorySubscriber>
 */
#[UseModel(NewsletterSendHistorySubscriber::class)]
final class NewsletterSendHistorySubscriberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(NewsletterSendHistoryStatusEnum::cases());

        return [
            'status'         => $status,
            'sent_at'        => NewsletterSendHistoryStatusEnum::SENT === $status ? $this->faker->dateTimeBetween('-1 hour', 'now') : null,
            'failed_at'      => NewsletterSendHistoryStatusEnum::FAILED === $status ? $this->faker->dateTimeBetween('-1 hour', 'now') : null,
            'failed_message' => NewsletterSendHistoryStatusEnum::FAILED === $status ? $this->faker->sentence() : null,
        ];
    }

    public function withNewsletterSendHistory(NewsletterSendHistory $sendHistory): static
    {
        return $this->state(fn(array $attributes): array => [
            'newsletter_send_history_id' => $sendHistory->id,
        ]);
    }

    public function withNewsletterSubscriber(NewsletterSubscriber $subscriber): static
    {
        return $this->state(fn(array $attributes): array => [
            'newsletter_subscriber_id' => $subscriber->id,
        ]);
    }

    public function sending(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status'         => NewsletterSendHistoryStatusEnum::SENDING,
            'sent_at'        => null,
            'failed_at'      => null,
            'failed_message' => null,
        ]);
    }

    public function sent(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status'         => NewsletterSendHistoryStatusEnum::SENT,
            'sent_at'        => $this->faker->dateTimeBetween('-1 hour', 'now'),
            'failed_at'      => null,
            'failed_message' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status'         => NewsletterSendHistoryStatusEnum::FAILED,
            'sent_at'        => null,
            'failed_at'      => $this->faker->dateTimeBetween('-1 hour', 'now'),
            'failed_message' => $this->faker->sentence(),
        ]);
    }
}
