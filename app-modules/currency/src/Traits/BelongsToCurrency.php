<?php

declare(strict_types=1);

namespace Misaf\Currency\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\Currency\Models\Currency;

trait BelongsToCurrency
{
    /**
     * @return BelongsTo<Currency, $this>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
