<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\VendraAffiliate\Models\Affiliate;

trait BelongsToAffiliate
{
    /**
     * @return BelongsTo<Affiliate, $this>
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }
}
