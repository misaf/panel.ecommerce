<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Misaf\VendraUserProfile\Models\UserProfile;

trait HasUserProfile
{
    /**
     * @return HasMany<UserProfile, $this>
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    /**
     * @return HasMany<UserProfile, $this>
     */
    public function userProfiles(): HasMany
    {
        return $this->profiles();
    }
}
