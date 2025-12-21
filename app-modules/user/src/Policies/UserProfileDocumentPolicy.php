<?php

declare(strict_types=1);

namespace Misaf\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\User\Models\User;
use Misaf\User\Models\UserProfileDocument;

final class UserProfileDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-profile-document');
    }

    /**
     * @param User $user
     * @param UserProfileDocument $userProfileDocument
     * @return bool
     */
    public function delete(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('delete-user-profile-document');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-document');
    }

    /**
     * @param User $user
     * @param UserProfileDocument $userProfileDocument
     * @return bool
     */
    public function forceDelete(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('force-delete-user-profile-document');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-document');
    }

    /**
     * @param User $user
     * @param UserProfileDocument $userProfileDocument
     * @return bool
     */
    public function replicate(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('replicate-user-profile-document');
    }

    /**
     * @param User $user
     * @param UserProfileDocument $userProfileDocument
     * @return bool
     */
    public function restore(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('restore-user-profile-document');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-document');
    }

    /**
     * @param User $user
     * @param UserProfileDocument $userProfileDocument
     * @return bool
     */
    public function update(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('update-user-profile-document');
    }

    /**
     * @param User $user
     * @param UserProfileDocument $userProfileDocument
     * @return bool
     */
    public function view(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('view-user-profile-document');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-document');
    }
}
