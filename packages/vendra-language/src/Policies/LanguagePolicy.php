<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraLanguage\Enums\LanguagePolicyEnum;
use Misaf\VendraLanguage\Models\Language;

final class LanguagePolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(LanguagePolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Language $language): bool
    {
        return $user->can(LanguagePolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(LanguagePolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Language $language): bool
    {
        return $user->can(LanguagePolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(LanguagePolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(LanguagePolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, Language $language): bool
    {
        return $user->can(LanguagePolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Language $language): bool
    {
        return $user->can(LanguagePolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(LanguagePolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, Language $language): bool
    {
        return $user->can(LanguagePolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Language $language): bool
    {
        return $user->can(LanguagePolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(LanguagePolicyEnum::VIEW_ANY->value);
    }
}
