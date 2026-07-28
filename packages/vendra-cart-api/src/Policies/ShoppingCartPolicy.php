<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Misaf\VendraCartApi\ApiResource\CartResource;
use Misaf\VendraSupport\Authorization\AuthorizesSandboxMode;

final class ShoppingCartPolicy
{
    use AuthorizesSandboxMode;

    public function viewAny(Authorizable $user): bool
    {
        return $user instanceof Authenticatable;
    }

    public function view(Authorizable $user, CartResource $cart): bool
    {
        return $user instanceof Authenticatable && $cart->isOwnedBy($user);
    }
}
