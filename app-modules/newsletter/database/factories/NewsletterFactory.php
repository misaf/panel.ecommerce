<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Tenant\Models\Tenant;

/**
 * @extends Factory<Newsletter>
 */
final class NewsletterFactory extends Factory
{
    /**
     * @var class-string<Newsletter>
     */
    protected $model = Newsletter::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
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
