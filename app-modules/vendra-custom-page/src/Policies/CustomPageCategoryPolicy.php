<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCustomPage\Enums\CustomPageCategoryPolicyEnum;
use Misaf\VendraCustomPage\Models\CustomPageCategory;

final class CustomPageCategoryPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::VIEW_ANY->value);
    }
}
