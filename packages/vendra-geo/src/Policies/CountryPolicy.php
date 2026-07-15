<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraGeo\Enums\CountryPolicyEnum;
use Misaf\VendraGeo\Models\Country;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class CountryPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(CountryPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Country $country): bool
    {
        return $user->can(CountryPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(CountryPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Country $country): bool
    {
        return $user->can(CountryPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(CountryPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(CountryPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, Country $country): bool
    {
        return $user->can(CountryPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Country $country): bool
    {
        return $user->can(CountryPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(CountryPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, Country $country): bool
    {
        return $user->can(CountryPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Country $country): bool
    {
        return $user->can(CountryPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(CountryPolicyEnum::VIEW_ANY->value);
    }
}
