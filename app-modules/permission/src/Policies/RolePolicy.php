<?php

declare(strict_types=1);

namespace Misaf\Permission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\Permission\Models\Role;
use Misaf\User\Models\User;

final class RolePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-role');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->can('delete-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-role');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can('force-delete-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-role');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function replicate(User $user, Role $role): bool
    {
        return $user->can('replicate-role');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->can('restore-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-role');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function update(User $user, Role $role): bool
    {
        return $user->can('update-role');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can('view-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-role');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function viewUsers(User $user, Role $role): bool
    {
        return $this->view($user, $role);
    }
}
