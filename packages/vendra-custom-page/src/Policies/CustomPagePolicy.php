<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCustomPage\Enums\CustomPagePolicyEnum;
use Misaf\VendraCustomPage\Models\CustomPage;

final class CustomPagePolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(CustomPagePolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(CustomPagePolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(CustomPagePolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(CustomPagePolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(CustomPagePolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(CustomPagePolicyEnum::VIEW_ANY->value);
    }
}
