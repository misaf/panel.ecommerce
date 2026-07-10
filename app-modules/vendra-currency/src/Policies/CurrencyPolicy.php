<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraCurrency\Enums\CurrencyPolicyEnum;
use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraSupport\Support\SandboxMode;

final class CurrencyPolicy
{
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Currency $currency): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Currency $currency): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, Currency $currency): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Currency $currency): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, Currency $currency): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Currency $currency): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return SandboxMode::enabled()
            || $user->can(CurrencyPolicyEnum::VIEW_ANY->value);
    }
}
