<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Misaf\VendraAffiliate\Enums\AffiliateClickPolicyEnum;
use Misaf\VendraAffiliate\Models\AffiliateClick;
use Misaf\VendraSupport\Concerns\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;
use Misaf\VendraSupport\Concerns\AuthorizesViewAbilities;
use Misaf\VendraSupport\Concerns\ResolvesPolicyPermissions;

final class AffiliateClickPolicy
{
    use AuthorizesDeleteAbilities;
    use AuthorizesSandboxMode;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return AffiliateClickPolicyEnum::class;
    }

    public function create(Authorizable $user): bool
    {
        return false;
    }

    public function update(Authorizable $user, AffiliateClick $affiliateClick): bool
    {
        return false;
    }
}
