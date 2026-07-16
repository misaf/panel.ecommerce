<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesForceDeleteAbilities
{
    public function forceDelete(Authorizable $user, Model $model): bool
    {
        return $this->allowed($user, 'ForceDelete');
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $this->allowed($user, 'ForceDeleteAny');
    }
}
