<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliatePayoutPolicyEnum;
use Misaf\VendraAffiliate\Models\AffiliatePayout;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;

final class AffiliatePayoutPolicy
{
    use AuthorizesSandboxMode;
    use HandlesAuthorization;

    /**
     * Payouts are created only through the settlement action.
     */
    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function delete(Authorizable $user, AffiliatePayout $affiliatePayout): bool
    {
        return false;
    }

    public function process(Authorizable $user): bool
    {
        return $user->can(AffiliatePayoutPolicyEnum::PROCESS->value);
    }

    public function update(Authorizable $user, AffiliatePayout $affiliatePayout): bool
    {
        return false;
    }

    public function view(Authorizable $user, AffiliatePayout $affiliatePayout): bool
    {
        return $user->can(AffiliatePayoutPolicyEnum::VIEW->value);
    }

    public function viewAny(Authorizable $user): bool
    {
        return $user->can(AffiliatePayoutPolicyEnum::VIEW_ANY->value);
    }
}
