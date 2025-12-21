<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\AuthifyLog\Models\AuthifyLog;

final class AuthifyLogPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can('create-authify-log');
    }

    public function delete(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('delete-authify-log');
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can('delete-any-authify-log');
    }

    public function forceDelete(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('force-delete-authify-log');
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can('force-delete-any-authify-log');
    }

    public function replicate(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('replicate-authify-log');
    }

    public function restore(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('restore-authify-log');
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can('restore-any-authify-log');
    }

    public function update(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('update-authify-log');
    }

    public function view(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can('view-authify-log');
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can('view-any-authify-log');
    }
}
