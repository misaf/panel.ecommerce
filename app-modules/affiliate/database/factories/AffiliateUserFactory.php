<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Affiliate\Models\Affiliate;
use Misaf\Affiliate\Models\AffiliateUser;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;

/**
 * @extends Factory<AffiliateUser>
 */
final class AffiliateUserFactory extends Factory
{
    /**
     * @var class-string<AffiliateUser>
     */
    protected $model = AffiliateUser::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id'         => Tenant::factory(),
            'affiliate_id'      => Affiliate::factory(),
            'user_id'           => User::factory(),
            'commission_earned' => 0,
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
