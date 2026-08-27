<?php

declare(strict_types=1);

namespace Misaf\VendraWishlistApi\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Misaf\VendraSupport\Authorization\AuthorizesSandboxMode;
use Misaf\VendraWishlistApi\ApiResource\WishlistResource;

final class CustomerWishlistPolicy
{
    use AuthorizesSandboxMode;

    public function viewAny(Authorizable $user): bool
    {
        return $user instanceof Authenticatable;
    }

    public function view(Authorizable $user, WishlistResource $wishlist): bool
    {
        return $user instanceof Authenticatable && $wishlist->isOwnedBy($user);
    }
}
