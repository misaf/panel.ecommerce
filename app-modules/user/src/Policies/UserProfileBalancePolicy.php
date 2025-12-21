<?php

declare(strict_types=1);

namespace Misaf\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\User\Models\User;
use Misaf\User\Models\UserProfileBalance;

final class UserProfileBalancePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-profile-balance');
    }

    /**
     * @param User $user
     * @param UserProfileBalance $userProfileBalance
     * @return bool
     */
    public function delete(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('delete-user-profile-balance');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-balance');
    }

    /**
     * @param User $user
     * @param UserProfileBalance $userProfileBalance
     * @return bool
     */
    public function forceDelete(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('force-delete-user-profile-balance');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-balance');
    }

    /**
     * @param User $user
     * @param UserProfileBalance $userProfileBalance
     * @return bool
     */
    public function replicate(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('replicate-user-profile-balance');
    }

    /**
     * @param User $user
     * @param UserProfileBalance $userProfileBalance
     * @return bool
     */
    public function restore(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('restore-user-profile-balance');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-balance');
    }

    /**
     * @param User $user
     * @param UserProfileBalance $userProfileBalance
     * @return bool
     */
    public function update(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('update-user-profile-balance');
    }

    /**
     * @param User $user
     * @param UserProfileBalance $userProfileBalance
     * @return bool
     */
    public function view(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('view-user-profile-balance');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-balance');
    }
}
