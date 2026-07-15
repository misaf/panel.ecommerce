<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliateClickPolicyEnum;
use Misaf\VendraAffiliate\Models\AffiliateClick;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class AffiliateClickPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    /**
     * Clicks are recorded by the redirect endpoint, never created manually.
     */
    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function delete(Authorizable $user, AffiliateClick $affiliateClick): bool
    {
        return $user->can(AffiliateClickPolicyEnum::DELETE->value);
    }

    public function deleteAny(Authorizable $user): bool
    {
        return $user->can(AffiliateClickPolicyEnum::DELETE_ANY->value);
    }

    public function update(Authorizable $user, AffiliateClick $affiliateClick): bool
    {
        return false;
    }

    public function view(Authorizable $user, AffiliateClick $affiliateClick): bool
    {
        return $user->can(AffiliateClickPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(AffiliateClickPolicyEnum::VIEW_ANY->value);
    }
}
