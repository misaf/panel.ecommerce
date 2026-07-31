<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StorefrontDeploymentStatus;
use App\Models\StorefrontDeployment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\VendraTenant\Models\Tenant;

/**
 * @extends Factory<StorefrontDeployment>
 */
final class StorefrontDeploymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<StorefrontDeployment>
     */
    protected $model = StorefrontDeployment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id'     => Tenant::factory(),
            'slug'          => fake()->unique()->slug(2),
            'domain'        => fake()->unique()->domainName(),
            'theme'         => 'default',
            'configuration' => [
                'name' => ['en' => fake()->company(), 'fa' => 'گل‌فروشی'],
            ],
            'status' => StorefrontDeploymentStatus::Pending,
        ];
    }
}
