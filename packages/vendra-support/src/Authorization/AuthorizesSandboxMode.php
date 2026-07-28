<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Authorization;

use Illuminate\Contracts\Auth\Access\Authorizable;

trait AuthorizesSandboxMode
{
    public function before(Authorizable $user, string $ability): ?bool
    {
        return SandboxMode::enabled() ? true : null;
    }
}
