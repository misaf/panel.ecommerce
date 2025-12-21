<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\Affiliate\Models\Affiliate;

trait HasAffiliate
{
    use HasAffiliateUser;

    /**
     * @return HasOne<Affiliate, $this>
     */
    public function latestAffiliate(): HasOne
    {
        return $this->hasOne(Affiliate::class)->latestOfMany();
    }

    /**
     * @return HasOne<Affiliate, $this>
     */
    public function oldestAffiliate(): HasOne
    {
        return $this->hasOne(Affiliate::class)->oldestOfMany();
    }

    /**
     * @return HasMany<Affiliate, $this>
     */
    public function affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }
}
