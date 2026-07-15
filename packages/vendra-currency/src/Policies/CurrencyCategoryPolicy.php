<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCurrency\Enums\CurrencyCategoryPolicyEnum;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class CurrencyCategoryPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, CurrencyCategory $currencyCategory): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(CurrencyCategoryPolicyEnum::VIEW_ANY->value);
    }
}
