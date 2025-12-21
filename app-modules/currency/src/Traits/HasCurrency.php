<?php

declare(strict_types=1);

namespace Misaf\Currency\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Misaf\Currency\Models\Currency;

trait HasCurrency
{
    /**
     * @return HasMany<Currency, $this>
     */
    public function currencies(): HasMany
    {
        return $this->hasMany(Currency::class);
    }
}
