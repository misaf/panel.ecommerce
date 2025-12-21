<?php

declare(strict_types=1);

namespace Misaf\User\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\User\Models\UserProfile;

trait HasUserProfile
{
    use HasUserProfileBalance;
    use HasUserProfileDocument;
    use HasUserProfilePhone;

    /**
     * @return HasOne<UserProfile, $this>
     */
    public function latestUserProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class)->latestOfMany();
    }

    /**
     * @return HasOne<UserProfile, $this>
     */
    public function oldestUserProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class)->oldestOfMany();
    }

    /**
     * @return HasMany<UserProfile, $this>
     */
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}
