<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraFaq\Enums\FaqPolicyEnum;
use Misaf\VendraFaq\Models\Faq;

final class FaqPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(FaqPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Faq $faq): bool
    {
        return $user->can(FaqPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(FaqPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Faq $faq): bool
    {
        return $user->can(FaqPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(FaqPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(FaqPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, Faq $faq): bool
    {
        return $user->can(FaqPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Faq $faq): bool
    {
        return $user->can(FaqPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(FaqPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, Faq $faq): bool
    {
        return $user->can(FaqPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Faq $faq): bool
    {
        return $user->can(FaqPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(FaqPolicyEnum::VIEW_ANY->value);
    }
}
