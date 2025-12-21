<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\AuthifyLog\Enums\AuthifyLogActionEnum;
use Misaf\AuthifyLog\Models\AuthifyLog;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;

/**
 * @extends Factory<AuthifyLog>
 */
final class AuthifyLogFactory extends Factory
{
    /**
     * @var class-string<AuthifyLog>
     */
    protected $model = AuthifyLog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id'  => Tenant::factory(),
            'user_id'    => User::factory(),
            'action'     => fake()->randomElement(AuthifyLogActionEnum::cases()),
            'ip_address' => fake()->ipv4(),
            'ip_country' => fake()->countryCode(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * @param Tenant $tenant
     * @return static
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn(): array => [
            'tenant_id' => $tenant->id,
        ]);
    }

    /**
     * @param User $user
     * @return static
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(): array => [
            'user_id' => $user->id,
        ]);
    }
}
