<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAuthifyLog\Enums\AuthifyLogPolicyEnum;
use Misaf\VendraAuthifyLog\Models\AuthifyLog;

final class AuthifyLogPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(AuthifyLogPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can(AuthifyLogPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(AuthifyLogPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can(AuthifyLogPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(AuthifyLogPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can(AuthifyLogPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can(AuthifyLogPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(AuthifyLogPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can(AuthifyLogPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, AuthifyLog $authifyLog): bool
    {
        return $user->can(AuthifyLogPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(AuthifyLogPolicyEnum::VIEW_ANY->value);
    }
}
