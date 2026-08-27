<?php

declare(strict_types=1);

namespace Misaf\VendraOrderApi\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Misaf\VendraOrderApi\ApiResource\OrderResource;
use Misaf\VendraSupport\Authorization\AuthorizesSandboxMode;

final class CustomerOrderPolicy
{
    use AuthorizesSandboxMode;

    public function viewAny(Authorizable $user): bool
    {
        return $user instanceof Authenticatable;
    }

    public function view(Authorizable $user, OrderResource $order): bool
    {
        return $user instanceof Authenticatable && $order->isOwnedBy($user);
    }
}
