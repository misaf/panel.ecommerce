<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Misaf\VendraCartApi\ApiResource\ShoppingCart;

final class ShoppingCartPolicy
{
    public function viewAny(Authorizable $user): bool
    {
        return $user instanceof Authenticatable;
    }

    public function view(Authorizable $user, ShoppingCart $cart): bool
    {
        return $user instanceof Authenticatable && $cart->isOwnedBy($user);
    }
}
