<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraGeo\Enums\CityPolicyEnum;
use Misaf\VendraGeo\Models\City;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class CityPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(CityPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, City $city): bool
    {
        return $user->can(CityPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(CityPolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, City $city): bool
    {
        return $user->can(CityPolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(CityPolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can(CityPolicyEnum::REORDER->value);
    }

    public function replicate(Authorizable $user, City $city): bool
    {
        return $user->can(CityPolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, City $city): bool
    {
        return $user->can(CityPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(CityPolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, City $city): bool
    {
        return $user->can(CityPolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, City $city): bool
    {
        return $user->can(CityPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(CityPolicyEnum::VIEW_ANY->value);
    }
}
