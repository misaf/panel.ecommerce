<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<Newsletter>
 */
#[UseModel(Newsletter::class)]
final class NewsletterFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'      => [
                'en' => $this->faker->sentences(1, true),
                'fa' => $this->faker->sentences(1, true),
            ],
            'description' => [
                'en' => $this->faker->realTextBetween(100, 200),
                'fa' => $this->faker->realTextBetween(100, 200),
            ],
            'status' => $this->faker->boolean(80),
        ];
    }

    /**
     * No-op without a tenant provider, since there is no `tenant_id` column.
     */
    public function forTenant(Model|int $tenant): static
    {
        if ( ! TenantAwareness::enabled()) {
            return $this;
        }

        return $this->state(fn(): array => [
            'tenant_id' => $tenant instanceof Model ? $tenant->getKey() : $tenant,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn(array $attributes): array => [
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
        ]);
    }

    public function enabled(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status' => true,
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn(array $attributes): array => [
            'status' => false,
        ]);
    }
}
