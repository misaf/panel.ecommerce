<?php

declare(strict_types=1);

namespace Misaf\UserMessenger\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\User\Models\User;
use Misaf\UserMessenger\Models\UserMessenger;

final class UserMessengerPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-user-messenger');
    }

    /**
     * @param User $user
     * @param UserMessenger $userMessenger
     * @return bool
     */
    public function delete(User $user, UserMessenger $userMessenger): bool
    {
        return $user->can('delete-user-messenger');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-user-messenger');
    }

    /**
     * @param User $user
     * @param UserMessenger $userMessenger
     * @return bool
     */
    public function forceDelete(User $user, UserMessenger $userMessenger): bool
    {
        return $user->can('force-delete-user-messenger');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-user-messenger');
    }

    /**
     * @param User $user
     * @param UserMessenger $userMessenger
     * @return bool
     */
    public function replicate(User $user, UserMessenger $userMessenger): bool
    {
        return $user->can('replicate-user-messenger');
    }

    /**
     * @param User $user
     * @param UserMessenger $userMessenger
     * @return bool
     */
    public function restore(User $user, UserMessenger $userMessenger): bool
    {
        return $user->can('restore-user-messenger');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-user-messenger');
    }

    /**
     * @param User $user
     * @param UserMessenger $userMessenger
     * @return bool
     */
    public function update(User $user, UserMessenger $userMessenger): bool
    {
        return $user->can('update-user-messenger');
    }

    /**
     * @param User $user
     * @param UserMessenger $userMessenger
     * @return bool
     */
    public function view(User $user, UserMessenger $userMessenger): bool
    {
        return $user->can('view-user-messenger');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-user-messenger');
    }
}
