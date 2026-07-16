<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliatePayoutPolicyEnum;
use Misaf\VendraAffiliate\Models\AffiliatePayout;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;
use Misaf\VendraSupport\Concerns\AuthorizesViewAbilities;
use Misaf\VendraSupport\Concerns\ResolvesPolicyPermissions;

final class AffiliatePayoutPolicy
{
    use AuthorizesSandboxMode;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return AffiliatePayoutPolicyEnum::class;
    }

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
        return $this->allowed($user, 'Process');
    }

    public function update(Authorizable $user, AffiliatePayout $affiliatePayout): bool
    {
        return false;
    }
}
