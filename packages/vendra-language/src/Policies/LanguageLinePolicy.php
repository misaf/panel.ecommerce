<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraLanguage\Enums\LanguageLinePolicyEnum;
use Misaf\VendraLanguage\Models\LanguageLine;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class LanguageLinePolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(LanguageLinePolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, LanguageLine $languageLine): bool
    {
        return $user->can(LanguageLinePolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(LanguageLinePolicyEnum::DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, LanguageLine $languageLine): bool
    {
        return $user->can(LanguageLinePolicyEnum::REPLICATE->value);
    }

    public function update(Authorizable $user, LanguageLine $languageLine): bool
    {
        return $user->can(LanguageLinePolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, LanguageLine $languageLine): bool
    {
        return $user->can(LanguageLinePolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(LanguageLinePolicyEnum::VIEW_ANY->value);
    }
}
