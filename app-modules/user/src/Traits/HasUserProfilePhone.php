<?php

declare(strict_types=1);

namespace Misaf\User\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\User\Models\UserProfilePhone;

trait HasUserProfilePhone
{
    /**
     * @return HasOne<UserProfilePhone, $this>
     */
    public function latestUserProfilePhone(): HasOne
    {
        return $this->hasOne(UserProfilePhone::class)->latestOfMany();
    }

    /**
     * @return HasOne<UserProfilePhone, $this>
     */
    public function oldestUserProfilePhone(): HasOne
    {
        return $this->hasOne(UserProfilePhone::class)->oldestOfMany();
    }

    /**
     * @return HasMany<UserProfilePhone, $this>
     */
    public function userProfilePhones(): HasMany
    {
        return $this->hasMany(UserProfilePhone::class);
    }
}
