<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCart\Enums\CartItemPolicyEnum;
use Misaf\VendraCart\Models\CartItem;
use Misaf\VendraSupport\Authorization\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesSandboxMode;
use Misaf\VendraSupport\Authorization\AuthorizesViewAbilities;
use Misaf\VendraSupport\Authorization\ResolvesPolicyPermissions;

final class CartItemPolicy
{
    use AuthorizesDeleteAbilities;
    use AuthorizesSandboxMode;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return CartItemPolicyEnum::class;
    }

    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function update(Authorizable $user, CartItem $cartItem): bool
    {
        return false;
    }
}
