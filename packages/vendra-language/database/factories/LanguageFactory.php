<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraSupport\Support\TenantAwareness;

/**
 * @extends Factory<Language>
 */
#[UseModel(Language::class)]
final class LanguageFactory extends Factory
{
    public function definition(): array
    {
        $isoCode = $this->faker->unique()->languageCode();

        return [
            'name'        => $isoCode,
            'description' => $this->faker->realTextBetween(100, 200),
            'slug'        => Str::slug($isoCode),
            'iso_code'    => $isoCode,
            'is_default'  => false,
            'position'    => $this->faker->numberBetween(1, 1000),
            'status'      => $this->faker->boolean(80),
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

    public function enabled(): static
    {
        return $this->state(fn(): array => ['status' => true]);
    }

    public function disabled(): static
    {
        return $this->state(fn(): array => ['status' => false]);
    }
}
