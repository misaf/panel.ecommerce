<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User;
use App\Models\User\UserProfilePhone;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserProfilePhonePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-profile-phone');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('delete-user-profile-phone');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-phone');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('force-delete-user-profile-phone');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-phone');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('replicate-user-profile-phone');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('restore-user-profile-phone');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-phone');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('update-user-profile-phone');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('view-user-profile-phone');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-phone');
    }
}
