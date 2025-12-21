<?php

declare(strict_types=1);

namespace Misaf\UserMessenger\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Tenant\Models\Tenant;
use Misaf\User\Models\User;
use Misaf\UserMessenger\Enums\UserMessengerPlatformEnum;
use Misaf\UserMessenger\Models\UserMessenger;

/**
 * @extends Factory<UserMessenger>
 */
final class UserMessengerFactory extends Factory
{
    /**
     * @var class-string<UserMessenger>
     */
    protected $model = UserMessenger::class;

    /**
     * Base definition with default platform.
     *
     * Note: This defaults to Telegram for convenience, but you should
     * explicitly use platform methods like telegram() for clarity.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id'   => User::factory(),
            'platform'  => UserMessengerPlatformEnum::Telegram,
            'key_name'  => 'telegram_chat_id',
            'key_value' => $this->generateTelegramChatId(),
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

    /**
     * Create a Telegram messenger instance.
     *
     * Explicitly sets the platform to Telegram with proper chat ID format.
     * This method should be used instead of relying on the default definition
     * to make platform selection clear and intentional.
     *
     * @return static
     */
    public function telegram(): static
    {
        return $this->state(fn(): array => [
            'platform'  => UserMessengerPlatformEnum::Telegram,
            'key_name'  => 'telegram_chat_id',
            'key_value' => $this->generateTelegramChatId(),
        ]);
    }

    /**
     * Generate a realistic Telegram chat ID.
     *
     * Telegram chat IDs are typically 9-10 digit integers within
     * the range of 100,000,000 to 9,999,999,999. This generator
     * produces values that match real Telegram chat ID patterns.
     *
     * @return string The generated chat ID as a string
     */
    private function generateTelegramChatId(): string
    {
        // Telegram chat IDs are typically 9-10 digit numbers
        return (string) fake()->numberBetween(100_000_000, 9_999_999_999);
    }
}
