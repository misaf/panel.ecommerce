<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesUpdateAbilities
{
    public function update(Authorizable $user, Model $model): bool
    {
        return $this->allowed($user, 'Update');
    }
}
