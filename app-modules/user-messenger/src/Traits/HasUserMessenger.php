<?php

declare(strict_types=1);

namespace Misaf\UserMessenger\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\UserMessenger\Models\UserMessenger;

trait HasUserMessenger
{
    /**
     * @return HasOne<UserMessenger, $this>
     */
    public function latestUserMessenger(): HasOne
    {
        return $this->hasOne(UserMessenger::class)->latestOfMany();
    }

    /**
     * @return HasOne<UserMessenger, $this>
     */
    public function oldestUserMessenger(): HasOne
    {
        return $this->hasOne(UserMessenger::class)->oldestOfMany();
    }

    /**
     * @return HasMany<UserMessenger, $this>
     */
    public function userMessengers(): HasMany
    {
        return $this->hasMany(UserMessenger::class);
    }
}
