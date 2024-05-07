<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User;
use App\Models\User\UserProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserProfilePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-user-profile');
    }

    public function delete(User $user, UserProfile $userProfile): bool
    {
        return $user->can('delete-user-profile');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile');
    }

    public function forceDelete(User $user, UserProfile $userProfile): bool
    {
        return $user->can('force-delete-user-profile');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile');
    }

    public function replicate(User $user, UserProfile $userProfile): bool
    {
        return $user->can('replicate-user-profile');
    }

    public function restore(User $user, UserProfile $userProfile): bool
    {
        return $user->can('restore-user-profile');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile');
    }

    public function update(User $user, UserProfile $userProfile): bool
    {
        return $user->can('update-user-profile');
    }

    public function view(User $user, UserProfile $userProfile): bool
    {
        return $user->can('view-user-profile');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile');
    }
}
