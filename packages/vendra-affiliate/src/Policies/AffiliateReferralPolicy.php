<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliateReferralPolicyEnum;
use Misaf\VendraAffiliate\Models\AffiliateReferral;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class AffiliateReferralPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    public function create(Authorizable $user): bool
    {
        return $user->can(AffiliateReferralPolicyEnum::CREATE->value);
    }

    public function delete(Authorizable $user, AffiliateReferral $affiliateReferral): bool
    {
        return $user->can(AffiliateReferralPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(AffiliateReferralPolicyEnum::DELETE_ANY->value);
    }

    /**
     * Attribution is immutable once recorded.
     */
    public function update(Authorizable $user, AffiliateReferral $affiliateReferral): bool
    {
        return false;
    }

    public function view(Authorizable $user, AffiliateReferral $affiliateReferral): bool
    {
        return $user->can(AffiliateReferralPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(AffiliateReferralPolicyEnum::VIEW_ANY->value);
    }
}
