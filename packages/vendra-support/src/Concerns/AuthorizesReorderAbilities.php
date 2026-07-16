<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;

trait AuthorizesReorderAbilities
{
    public function reorder(Authorizable $user): bool
    {
        return $this->allowed($user, 'Reorder');
    }
}
