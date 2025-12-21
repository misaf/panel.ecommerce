<?php

declare(strict_types=1);

namespace Misaf\Permission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Permission\Models\Permission;
use Misaf\User\Models\User;

final class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-permission');
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('delete-permission');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-permission');
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->can('force-delete-permission');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-permission');
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function replicate(User $user, Permission $permission): bool
    {
        return $user->can('replicate-permission');
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function restore(User $user, Permission $permission): bool
    {
        return $user->can('restore-permission');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-permission');
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->can('update-permission');
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->can('view-permission');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-permission');
    }

    /**
     * @param User $user
     * @param Permission $permission
     * @return bool
     */
    public function viewUsers(User $user, Permission $permission): bool
    {
        return $this->view($user, $permission);
    }
}
