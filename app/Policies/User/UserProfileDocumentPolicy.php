<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User;
use App\Models\User\UserProfileDocument;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserProfileDocumentPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-user-profile-document');
    }

    public function delete(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('delete-user-profile-document');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-document');
    }

    public function forceDelete(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('force-delete-user-profile-document');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-document');
    }

    public function replicate(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('replicate-user-profile-document');
    }

    public function restore(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('restore-user-profile-document');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-document');
    }

    public function update(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('update-user-profile-document');
    }

    public function view(User $user, UserProfileDocument $userProfileDocument): bool
    {
        return $user->can('view-user-profile-document');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-document');
    }
}
