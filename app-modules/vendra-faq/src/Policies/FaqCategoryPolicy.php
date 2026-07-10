<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraFaq\Enums\FaqCategoryPolicyEnum;
use Misaf\VendraFaq\Models\FaqCategory;

final class FaqCategoryPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(FaqCategoryPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, FaqCategory $faqCategory): bool
    {
        return $user->can(FaqCategoryPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(FaqCategoryPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, FaqCategory $faqCategory): bool
    {
        return $user->can(FaqCategoryPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(FaqCategoryPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(FaqCategoryPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, FaqCategory $faqCategory): bool
    {
        return $user->can(FaqCategoryPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, FaqCategory $faqCategory): bool
    {
        return $user->can(FaqCategoryPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(FaqCategoryPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, FaqCategory $faqCategory): bool
    {
        return $user->can(FaqCategoryPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, FaqCategory $faqCategory): bool
    {
        return $user->can(FaqCategoryPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(FaqCategoryPolicyEnum::VIEW_ANY->value);
    }
}
