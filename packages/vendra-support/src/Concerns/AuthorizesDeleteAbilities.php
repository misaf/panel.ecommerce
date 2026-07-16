<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesDeleteAbilities
{
    public function delete(Authorizable $user, Model $model): bool
    {
        return $this->allowed($user, 'Delete');
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $this->allowed($user, 'DeleteAny');
    }
}
