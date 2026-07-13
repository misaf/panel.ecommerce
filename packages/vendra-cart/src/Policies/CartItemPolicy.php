<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCart\Enums\CartItemPolicyEnum;
use Misaf\VendraCart\Models\CartItem;

final class CartItemPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function delete(Authorizable $user, CartItem $cartItem): bool
    {
        return $user->can(CartItemPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(CartItemPolicyEnum::DELETE_ANY->value);
    }

    public function update(Authorizable $user, CartItem $cartItem): bool
    {
        return false;
    }

    public function view(Authorizable $user, CartItem $cartItem): bool
    {
        return $user->can(CartItemPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(CartItemPolicyEnum::VIEW_ANY->value);
    }
}
