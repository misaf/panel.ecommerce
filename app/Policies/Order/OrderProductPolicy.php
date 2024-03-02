<?php

declare(strict_types=1);

namespace App\Policies\Order;

use App\Models\Order\OrderProduct;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class OrderProductPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-order-product');
    }

    public function delete(User $user, OrderProduct $orderProduct): bool
    {
        return $user->can('delete-order-product');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-order-product');
    }

    public function forceDelete(User $user, OrderProduct $orderProduct): bool
    {
        return $user->can('force-delete-order-product');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-order-product');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-order-product');
    }

    public function replicate(User $user, OrderProduct $orderProduct): bool
    {
        return $user->can('replicate-order-product');
    }

    public function restore(User $user, OrderProduct $orderProduct): bool
    {
        return $user->can('restore-order-product');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-order-product');
    }

    public function update(User $user, OrderProduct $orderProduct): bool
    {
        return $user->can('update-order-product');
    }

    public function view(User $user, OrderProduct $orderProduct): bool
    {
        return $user->can('view-order-product');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-order-product');
    }
}
