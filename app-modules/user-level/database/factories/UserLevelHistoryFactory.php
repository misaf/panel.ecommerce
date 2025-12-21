<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;
use Misaf\UserLevel\Models\UserLevel;
use Misaf\UserLevel\Models\UserLevelHistory;

/**
 * @extends Factory<UserLevelHistory>
 */
final class UserLevelHistoryFactory extends Factory
{
    /**
     * @var class-string<UserLevelHistory>
     */
    protected $model = UserLevelHistory::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id'     => Tenant::factory(),
            'user_id'       => User::factory(),
            'user_level_id' => UserLevel::factory(),
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
