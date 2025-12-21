<?php

declare(strict_types=1);

namespace Misaf\User\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\User\Models\UserProfile;

trait BelongsToUserProfile
{
    /**
     * @return BelongsTo<UserProfile, $this>
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }
}
