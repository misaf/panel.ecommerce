<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliateCommissionPolicyEnum;
use Misaf\VendraAffiliate\Models\AffiliateCommission;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class AffiliateCommissionPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function approve(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::APPROVE->value);
    }

    /**
     * Ledger entries are written only by the crediting actions.
     */
    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function delete(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::DELETE_ANY->value);
    }

    public function restore(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::RESTORE->value);
    }

    public function restoreAny(Authorizable $user): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::RESTORE_ANY->value);
    }

    public function reverse(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::REVERSE->value);
    }

    public function update(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return false;
    }

    public function view(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(AffiliateCommissionPolicyEnum::VIEW_ANY->value);
    }
}
