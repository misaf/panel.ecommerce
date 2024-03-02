<?php

declare(strict_types=1);

namespace App\Policies\User;

use App\Models\User;
use App\Models\User\UserProfileBalance;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserProfileBalancePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('create-user-profile-balance');
    }

    public function delete(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('delete-user-profile-balance');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-profile-balance');
    }

    public function forceDelete(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('force-delete-user-profile-balance');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-profile-balance');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder-user-profile-balance');
    }

    public function replicate(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('replicate-user-profile-balance');
    }

    public function restore(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('restore-user-profile-balance');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-profile-balance');
    }

    public function update(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('update-user-profile-balance');
    }

    public function view(User $user, UserProfileBalance $userProfileBalance): bool
    {
        return $user->can('view-user-profile-balance');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-profile-balance');
    }
}
