<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\Affiliate\Models\AffiliateUser;

trait HasAffiliateUser
{
    /**
     * @return HasOne<AffiliateUser, $this>
     */
    public function latestAffiliateUser(): HasOne
    {
        return $this->hasOne(AffiliateUser::class)->latestOfMany();
    }

    /**
     * @return HasOne<AffiliateUser, $this>
     */
    public function oldestAffiliateUser(): HasOne
    {
        return $this->hasOne(AffiliateUser::class)->oldestOfMany();
    }

    /**
     * @return HasMany<AffiliateUser, $this>
     */
    public function affiliateUsers(): HasMany
    {
        return $this->hasMany(AffiliateUser::class);
    }
}
