<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Transaction extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $casts = [
        'id'             => 'integer',
        'reference_code' => 'string',
        'amount'         => 'integer',
        'status'         => TransactionStatusEnum::class,
    ];

    protected $fillable = [
        'reference_code',
        'amount',
        'status',
    ];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
