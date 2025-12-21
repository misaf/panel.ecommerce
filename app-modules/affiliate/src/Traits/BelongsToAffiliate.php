<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\Affiliate\Models\Affiliate;

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
