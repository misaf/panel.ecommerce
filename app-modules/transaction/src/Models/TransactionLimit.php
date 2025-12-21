<?php

declare(strict_types=1);

namespace Misaf\Transaction\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Misaf\Transaction\Database\Factories\TransactionLimitFactory;
use Misaf\Transaction\Enums\TransactionTypeEnum;
use Misaf\User\Traits\BelongsToUser;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $user_id
 * @property TransactionTypeEnum $transaction_type
 * @property int $amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class TransactionLimit extends Model
{
    use BelongsToUser;
    /** @use HasFactory<TransactionLimitFactory> */
    use HasFactory;
    use LogsActivity;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'               => 'integer',
        'user_id'          => 'integer',
        'transaction_type' => TransactionTypeEnum::class,
        'amount'           => 'integer',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
    ];

    /**
     * @param  Builder<self>  $builder
     */
    public function scopeDeposit(Builder $builder): void
    {
        $builder->where('transaction_type', TransactionTypeEnum::Deposit);
    }

    /**
     * @param  Builder<self>  $builder
     */
    public function scopeWithdrawal(Builder $builder): void
    {
        $builder->where('transaction_type', TransactionTypeEnum::Withdrawal);
    }

    /**
     * @param  Builder<self>  $builder
     */
    public function scopeCommission(Builder $builder): void
    {
        $builder->where('transaction_type', TransactionTypeEnum::Commission);
    }

    /**
     * @param  Builder<self>  $builder
     */
    public function scopeBonus(Builder $builder): void
    {
        $builder->where('transaction_type', TransactionTypeEnum::Bonus);
    }

    /**
     * @param  Builder<self>  $builder
     */
    public function scopeTransfer(Builder $builder): void
    {
        $builder->where('transaction_type', TransactionTypeEnum::Transfer);
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
