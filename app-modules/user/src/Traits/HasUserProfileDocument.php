<?php

declare(strict_types=1);

namespace Misaf\User\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\User\Models\UserProfileDocument;

trait HasUserProfileDocument
{
    /**
     * @return HasOne<UserProfileDocument, $this>
     */
    public function latestUserProfileDocument(): HasOne
    {
        return $this->hasOne(UserProfileDocument::class)->latestOfMany();
    }

    /**
     * @return HasOne<UserProfileDocument, $this>
     */
    public function oldestUserProfileDocument(): HasOne
    {
        return $this->hasOne(UserProfileDocument::class)->oldestOfMany();
    }

    /**
     * @return HasMany<UserProfileDocument, $this>
     */
    public function userProfileDocuments(): HasMany
    {
        return $this->hasMany(UserProfileDocument::class);
    }
}
