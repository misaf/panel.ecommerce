<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\AuthifyLog\Models\AuthifyLog;
use Misaf\User\Models\User;

final class AuthifyLogPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-authify-log');
    }

    /**
     * @param User $user
     * @param AuthifyLog $authifyLog
     * @return bool
     */
    public function delete(User $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('delete-authify-log');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any-authify-log');
    }

    /**
     * @param User $user
     * @param AuthifyLog $authifyLog
     * @return bool
     */
    public function forceDelete(User $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('force-delete-authify-log');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any-authify-log');
    }

    /**
     * @param User $user
     * @param AuthifyLog $authifyLog
     * @return bool
     */
    public function replicate(User $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('replicate-authify-log');
    }

    /**
     * @param User $user
     * @param AuthifyLog $authifyLog
     * @return bool
     */
    public function restore(User $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('restore-authify-log');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any-authify-log');
    }

    /**
     * @param User $user
     * @param AuthifyLog $authifyLog
     * @return bool
     */
    public function update(User $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('update-authify-log');
    }

    /**
     * @param User $user
     * @param AuthifyLog $authifyLog
     * @return bool
     */
    public function view(User $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('view-authify-log');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-authify-log');
    }
}
