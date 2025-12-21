<?php

declare(strict_types=1);

namespace Misaf\Transaction\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\Transaction\Models\Transaction;

trait BelongsToTransaction
{
    use BelongsToTransactionGateway;

    /**
     * @return BelongsTo<Transaction, $this>
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
