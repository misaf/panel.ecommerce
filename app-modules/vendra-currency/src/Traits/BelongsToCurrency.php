<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\VendraCurrency\Models\Currency;

trait BelongsToCurrency /** @phpstan-ignore trait.unused */
{
    /**
     * @return BelongsTo<Currency, $this>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
