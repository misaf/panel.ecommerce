<?php

declare(strict_types=1);

namespace Misaf\User\Traits;

use Misaf\User\Models\User;
use Misaf\User\Models\UserProfile;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;

trait BelongsToUserThroughUserProfile
{
    use TraitsBelongsToThrough;

    /**
     * @return BelongsToThrough<User, $this>
     */
    public function user(): BelongsToThrough
    {
        return $this->belongsToThrough(User::class, UserProfile::class);
    }
}
