<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliateCommissionPolicyEnum;
use Misaf\VendraAffiliate\Models\AffiliateCommission;
use Misaf\VendraSupport\Concerns\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesRestoreAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;
use Misaf\VendraSupport\Concerns\AuthorizesViewAbilities;
use Misaf\VendraSupport\Concerns\ResolvesPolicyPermissions;

final class AffiliateCommissionPolicy
{
    use AuthorizesDeleteAbilities;
    use AuthorizesRestoreAbilities;
    use AuthorizesSandboxMode;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return AffiliateCommissionPolicyEnum::class;
    }

    public function approve(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return $this->allowed($user, 'Approve');
    }

    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function reverse(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return $this->allowed($user, 'Reverse');
    }

    public function update(Authorizable $user, AffiliateCommission $affiliateCommission): bool
    {
        return false;
    }
}
