<?php

declare(strict_types=1);

namespace Misaf\Transaction\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\Transaction\Models\TransactionGateway;

trait BelongsToTransactionGateway
{
    /**
     * @return BelongsTo<TransactionGateway, $this>
     */
    public function transactionGateway(): BelongsTo
    {
        return $this->belongsTo(TransactionGateway::class);
    }
}
