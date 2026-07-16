<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Misaf\VendraSupport\Concerns\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesViewAbilities;
use Misaf\VendraSupport\Concerns\ResolvesPolicyPermissions;

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
