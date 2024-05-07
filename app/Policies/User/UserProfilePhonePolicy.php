<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User;
use App\Models\User\UserProfilePhone;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserProfilePhonePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-user-profile-phone');
    }

    public function delete(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('delete-user-profile-phone');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-phone');
    }

    public function forceDelete(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('force-delete-user-profile-phone');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-phone');
    }

    public function replicate(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('replicate-user-profile-phone');
    }

    public function restore(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('restore-user-profile-phone');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-phone');
    }

    public function update(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('update-user-profile-phone');
    }

    public function view(User $user, UserProfilePhone $userProfilePhone): bool
    {
        return $user->can('view-user-profile-phone');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-phone');
    }
}
