<?php

declare(strict_types=1);

namespace Misaf\Transaction\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Misaf\Transaction\Models\Transaction;
use Misaf\Transaction\Models\TransactionTransfer;
use Misaf\User\Models\User;

/**
 * @extends Factory<TransactionTransfer>
 */
final class TransactionTransferFactory extends Factory
{
    /**
     * @var class-string<TransactionTransfer>
     */
    protected $model = TransactionTransfer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'user_id'        => User::factory(),
        ];
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
