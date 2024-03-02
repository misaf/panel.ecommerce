<?php

declare(strict_types=1);

namespace App\Policies\Permission;

use App\Models\Permission\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class PermissionPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-permission');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('delete-permission');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-permission');
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->can('force-delete-permission');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-permission');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-permission');
    }

    public function replicate(User $user, Permission $permission): bool
    {
        return $user->can('replicate-permission');
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->can('restore-permission');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-permission');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->can('update-permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can('view-permission');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-permission');
    }
}
