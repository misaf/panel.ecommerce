<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\User\Models\User;
use Misaf\UserLevel\Models\UserLevelHistory;

final class UserLevelHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-level-history');
    }

    /**
     * @param User $user
     * @param UserLevelHistory $userLevelHistory
     * @return bool
     */
    public function delete(User $user, UserLevelHistory $userLevelHistory): bool
    {
        return $user->can('delete-user-level-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-level-history');
    }

    /**
     * @param User $user
     * @param UserLevelHistory $userLevelHistory
     * @return bool
     */
    public function forceDelete(User $user, UserLevelHistory $userLevelHistory): bool
    {
        return $user->can('force-delete-user-level-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-level-history');
    }

    /**
     * @param User $user
     * @param UserLevelHistory $userLevelHistory
     * @return bool
     */
    public function replicate(User $user, UserLevelHistory $userLevelHistory): bool
    {
        return $user->can('replicate-user-level-history');
    }

    /**
     * @param User $user
     * @param UserLevelHistory $userLevelHistory
     * @return bool
     */
    public function restore(User $user, UserLevelHistory $userLevelHistory): bool
    {
        return $user->can('restore-user-level-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-level-history');
    }

    /**
     * @param User $user
     * @param UserLevelHistory $userLevelHistory
     * @return bool
     */
    public function update(User $user, UserLevelHistory $userLevelHistory): bool
    {
        return $user->can('update-user-level-history');
    }

    /**
     * @param User $user
     * @param UserLevelHistory $userLevelHistory
     * @return bool
     */
    public function view(User $user, UserLevelHistory $userLevelHistory): bool
    {
        return $user->can('view-user-level-history');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-level-history');
    }
}
