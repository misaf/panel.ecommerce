<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesViewAbilities
{
    public function view(Authorizable $user, Model $model): bool
    {
        return $this->allowed($user, 'View');
    }

    public function viewAny(Authorizable $user): bool
    {
        return $this->allowed($user, 'ViewAny');
    }
}
