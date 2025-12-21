<?php

declare(strict_types=1);

namespace Misaf\Permission\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Permission\Models\Permission;
use Misaf\Tenant\Models\Tenant;

/**
 * @extends Factory<Permission>
 */
final class PermissionFactory extends Factory
{
    /**
     * @var class-string<Permission>
     */
    protected $model = Permission::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => fake()->unique()->sentence(3),
            'guard_name' => null,
        ];
    }

    /**
     * @param Tenant $tenant
     * @return static
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(function (array $attributes) use ($tenant) {
            return [
                'tenant_id' => $tenant->id,
            ];
        });
    }
}
