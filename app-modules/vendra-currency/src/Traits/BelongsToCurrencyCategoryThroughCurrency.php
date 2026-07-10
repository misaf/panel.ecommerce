<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Traits;

use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Znck\Eloquent\Relations\BelongsToThrough;

trait BelongsToCurrencyCategoryThroughCurrency /** @phpstan-ignore trait.unused */
{
    /**
     * @return BelongsToThrough<CurrencyCategory, $this>
     */
    public function currencyCategory(): BelongsToThrough
    {
        return $this->belongsToThrough(CurrencyCategory::class, Currency::class);
    }
}
