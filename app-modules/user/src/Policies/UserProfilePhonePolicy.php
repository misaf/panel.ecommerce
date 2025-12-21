<?php

declare(strict_types=1);

namespace Misaf\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\User\Models\User;
use Misaf\User\Models\UserProfilePhone;

final class UserProfilePhonePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-profile-phone');
    }

    /**
     * @param User $user
     * @param UserProfilePhone $userProfilePhone
     * @return bool
     */
    public function delete(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('delete-user-profile-phone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-phone');
    }

    /**
     * @param User $user
     * @param UserProfilePhone $userProfilePhone
     * @return bool
     */
    public function forceDelete(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('force-delete-user-profile-phone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-phone');
    }

    /**
     * @param User $user
     * @param UserProfilePhone $userProfilePhone
     * @return bool
     */
    public function replicate(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('replicate-user-profile-phone');
    }

    /**
     * @param User $user
     * @param UserProfilePhone $userProfilePhone
     * @return bool
     */
    public function restore(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('restore-user-profile-phone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-phone');
    }

    /**
     * @param User $user
     * @param UserProfilePhone $userProfilePhone
     * @return bool
     */
    public function update(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('update-user-profile-phone');
    }

    /**
     * @param User $user
     * @param UserProfilePhone $userProfilePhone
     * @return bool
     */
    public function view(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('view-user-profile-phone');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-phone');
    }
}
