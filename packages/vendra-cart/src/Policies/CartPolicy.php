<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCart\Enums\CartPolicyEnum;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class CartPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function delete(Authorizable $user, Cart $cart): bool
    {
        return $user->can(CartPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(CartPolicyEnum::DELETE_ANY->value);
    }

    public function update(Authorizable $user, Cart $cart): bool
    {
        return false;
    }

    public function view(Authorizable $user, Cart $cart): bool
    {
        return $user->can(CartPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(CartPolicyEnum::VIEW_ANY->value);
    }
}
