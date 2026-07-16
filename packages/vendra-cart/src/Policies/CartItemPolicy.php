<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCart\Enums\CartItemPolicyEnum;
use Misaf\VendraCart\Models\CartItem;
use Misaf\VendraSupport\Concerns\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;
use Misaf\VendraSupport\Concerns\AuthorizesViewAbilities;
use Misaf\VendraSupport\Concerns\ResolvesPolicyPermissions;

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
