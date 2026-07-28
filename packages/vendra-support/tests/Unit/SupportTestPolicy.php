<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Misaf\VendraSupport\Authorization\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesViewAbilities;
use Misaf\VendraSupport\Authorization\ResolvesPolicyPermissions;

final class SupportTestPolicy
{
    use AuthorizesDeleteAbilities;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return SupportTestPolicyEnum::class;
    }
}
