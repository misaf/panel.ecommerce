<?php

declare(strict_types=1);

namespace Misaf\Currency\Traits;

use Misaf\Currency\Models\Currency;
use Misaf\Currency\Models\CurrencyCategory;
use Znck\Eloquent\Relations\BelongsToThrough;

trait BelongsToCurrencyCategoryThroughCurrency
{
    /**
     * @return BelongsToThrough<CurrencyCategory, $this>
     */
    public function currencyCategory(): BelongsToThrough
    {
        return $this->belongsToThrough(CurrencyCategory::class, Currency::class);
    }
}
