<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesRestoreAbilities
{
    public function restore(Authorizable $user, Model $model): bool
    {
        return $this->allowed($user, 'Restore');
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $this->allowed($user, 'RestoreAny');
    }
}
