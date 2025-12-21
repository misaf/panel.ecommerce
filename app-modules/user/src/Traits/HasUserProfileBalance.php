<?php

declare(strict_types=1);

namespace Misaf\User\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\User\Models\UserProfileBalance;

trait HasUserProfileBalance
{
    /**
     * @return HasOne<UserProfileBalance, $this>
     */
    public function latestUserProfileBalance(): HasOne
    {
        return $this->hasOne(UserProfileBalance::class)->latestOfMany();
    }

    /**
     * @return HasOne<UserProfileBalance, $this>
     */
    public function oldestUserProfileBalance(): HasOne
    {
        return $this->hasOne(UserProfileBalance::class)->oldestOfMany();
    }

    /**
     * @return HasMany<UserProfileBalance, $this>
     */
    public function userProfileBalances(): HasMany
    {
        return $this->hasMany(UserProfileBalance::class);
    }
}
