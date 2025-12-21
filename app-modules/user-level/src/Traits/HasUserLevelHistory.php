<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\UserLevel\Models\UserLevelHistory;

trait HasUserLevelHistory
{
    /**
     * @return HasOne<UserLevelHistory, $this>
     */
    public function latestUserLevelHistory(): HasOne
    {
        return $this->hasOne(UserLevelHistory::class)->latestOfMany();
    }

    /**
     * @return HasOne<UserLevelHistory, $this>
     */
    public function oldestUserLevelHistory(): HasOne
    {
        return $this->hasOne(UserLevelHistory::class)->oldestOfMany();
    }

    /**
     * @return HasMany<UserLevelHistory, $this>
     */
    public function userLevelHistories(): HasMany
    {
        return $this->hasMany(UserLevelHistory::class);
    }
}
