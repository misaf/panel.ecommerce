<?php

declare(strict_types=1);

namespace Database\Factories\Permission;

use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

final class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->sentence(),
            'guard_name' => null,
        ];
    }
}
