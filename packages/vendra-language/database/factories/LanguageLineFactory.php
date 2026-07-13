<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraLanguage\Enums\LanguageLineGroupEnum;
use Misaf\VendraLanguage\Models\LanguageLine;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<LanguageLine>
 */
#[UseModel(LanguageLine::class)]
final class LanguageLineFactory extends Factory
{
    public function definition(): array
    {
        /** @var LanguageLineGroupEnum $group */
        $group = $this->faker->randomElement(LanguageLineGroupEnum::cases());

        return [
            'group' => $group->value,
            'key'   => fake()->word(),
            'text'  => ['en' => fake()->word()],
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
}
