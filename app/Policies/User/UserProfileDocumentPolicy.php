<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User;
use App\Models\User\UserProfileDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserProfileDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-profile-document');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('delete-user-profile-document');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-document');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('force-delete-user-profile-document');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-document');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('replicate-user-profile-document');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('restore-user-profile-document');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-document');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('update-user-profile-document');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('view-user-profile-document');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-document');
    }
}
