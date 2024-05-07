<?php

declare(strict_types=1);

namespace App\Policies\Order;

use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class OrderPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-order');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->can('delete-order');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-order');
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->can('force-delete-order');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-order');
    }

    public function replicate(User $user, Order $order): bool
    {
        return $user->can('replicate-order');
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->can('restore-order');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-order');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('update-order');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('view-order');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-order');
    }
}
