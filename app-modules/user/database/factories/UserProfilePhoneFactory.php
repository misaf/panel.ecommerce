<?php

declare(strict_types=1);

namespace Misaf\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Enums\UserProfilePhoneStatusEnum;
use Misaf\User\Models\UserProfile;
use Misaf\User\Models\UserProfilePhone;
use Misaf\User\Services\UserProfilePhoneService;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * @extends Factory<UserProfilePhone>
 */
final class UserProfilePhoneFactory extends Factory
{
    /**
     * @var class-string<UserProfilePhone>
     */
    protected $model = UserProfilePhone::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Supported countries and their locale for faker
        $countryLocales = [
            'US' => 'en_US',
            'GB' => 'en_GB',
            'DE' => 'de_DE',
            'FR' => 'fr_FR',
            'IT' => 'it_IT',
            'ES' => 'es_ES',
            'NL' => 'nl_NL',
        ];
        $country = fake()->randomElement(array_keys($countryLocales));
        $locale = $countryLocales[$country];
        $faker = \Faker\Factory::create($locale);
        $rawPhone = $faker->phoneNumber();
        // Try to extract digits and prepend country code for E.164
        $digits = preg_replace('/\D+/', '', $rawPhone);
        $countryCodes = [
            'US' => '+1', 'GB' => '+44', 'DE' => '+49', 'FR' => '+33',
            'IT' => '+39', 'ES' => '+34', 'NL' => '+31',
        ];
        $e164 = ($countryCodes[$country] ?? '+1') . $digits;
        $phone = new PhoneNumber($e164, $country);

        return [
            'tenant_id'        => Tenant::factory(),
            'user_profile_id'  => UserProfile::factory(),
            'country'          => $country,
            'phone'            => $phone,
            'phone_normalized' => UserProfilePhoneService::phoneNormalized($phone),
            'phone_national'   => UserProfilePhoneService::phoneNational($country, $phone),
            'phone_e164'       => UserProfilePhoneService::phoneE164($country, $phone),
            'status'           => fake()->randomElement(UserProfilePhoneStatusEnum::cases()),
            'verified_at'      => fake()->optional(0.7)->dateTimeBetween('-7 days', 'now'),
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
     * Indicate that the phone is verified.
     */
    public function verified(): self
    {
        return $this->state(fn(array $attributes) => [
            'status'      => UserProfilePhoneStatusEnum::Approved,
            'verified_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Indicate that the phone is not verified.
     */
    public function unverified(): self
    {
        return $this->state(fn(array $attributes) => [
            'status'      => UserProfilePhoneStatusEnum::Pending,
            'verified_at' => null,
        ]);
    }
}
