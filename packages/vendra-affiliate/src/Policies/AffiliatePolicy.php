<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliatePolicyEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class AffiliatePolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(AffiliatePolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can(AffiliatePolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(AffiliatePolicyEnum::DELETE_ANY->value);
    }

    public function forceDelete(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can(AffiliatePolicyEnum::FORCE_DELETE->value);
    }

    public function forceDeleteAny(Authorizable $user): bool
    {
        return $user->can(AffiliatePolicyEnum::FORCE_DELETE_ANY->value);
    }

    public function replicate(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can(AffiliatePolicyEnum::REPLICATE->value);
    }

    public function restore(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can(AffiliatePolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(AffiliatePolicyEnum::RESTORE_ANY->value);
    }

    public function update(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can(AffiliatePolicyEnum::UPDATE->value);
    }

    public function view(Authorizable $user, Affiliate $affiliate): bool
    {
        return $user->can(AffiliatePolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(AffiliatePolicyEnum::VIEW_ANY->value);
    }
}
