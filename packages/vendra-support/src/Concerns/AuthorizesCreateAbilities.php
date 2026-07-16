<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;

trait AuthorizesCreateAbilities
{
    public function create(Authorizable $user): bool
    {
        return $this->allowed($user, 'Create');
    }
}
