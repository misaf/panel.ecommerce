<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraGeo\Enums\StatePolicyEnum;
use Misaf\VendraGeo\Models\State;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class StatePolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(StatePolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, State $state): bool
    {
        return $user->can(StatePolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(StatePolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, State $state): bool
    {
        return $user->can(StatePolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(StatePolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(StatePolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, State $state): bool
    {
        return $user->can(StatePolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, State $state): bool
    {
        return $user->can(StatePolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(StatePolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, State $state): bool
    {
        return $user->can(StatePolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, State $state): bool
    {
        return $user->can(StatePolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(StatePolicyEnum::VIEW_ANY->value);
    }
}
