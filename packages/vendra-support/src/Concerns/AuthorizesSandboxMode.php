<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Concerns;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraSupport\Support\SandboxMode;

trait AuthorizesSandboxMode
{
    public function before(Authorizable $user, string $ability): ?bool
    {
        return SandboxMode::enabled() ? true : null;
    }
}
