<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\User\Models\User;
use Misaf\UserLevel\Models\UserLevel;

final class UserLevelPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-level');
    }

    /**
     * @param User $user
     * @param UserLevel $userLevel
     * @return bool
     */
    public function delete(User $user, UserLevel $userLevel): bool
    {
        return $user->can('delete-user-level');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-level');
    }

    /**
     * @param User $user
     * @param UserLevel $userLevel
     * @return bool
     */
    public function forceDelete(User $user, UserLevel $userLevel): bool
    {
        return $user->can('force-delete-user-level');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-level');
    }

    /**
     * @param User $user
     * @param UserLevel $userLevel
     * @return bool
     */
    public function replicate(User $user, UserLevel $userLevel): bool
    {
        return $user->can('replicate-user-level');
    }

    /**
     * @param User $user
     * @param UserLevel $userLevel
     * @return bool
     */
    public function restore(User $user, UserLevel $userLevel): bool
    {
        return $user->can('restore-user-level');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-level');
    }

    /**
     * @param User $user
     * @param UserLevel $userLevel
     * @return bool
     */
    public function update(User $user, UserLevel $userLevel): bool
    {
        return $user->can('update-user-level');
    }

    /**
     * @param User $user
     * @param UserLevel $userLevel
     * @return bool
     */
    public function view(User $user, UserLevel $userLevel): bool
    {
        return $user->can('view-user-level');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-level');
    }
}
