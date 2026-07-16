<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesReplicateAbilities
{
    public function replicate(Authorizable $user, Model $model): bool
    {
        return $this->allowed($user, 'Replicate');
    }
}
