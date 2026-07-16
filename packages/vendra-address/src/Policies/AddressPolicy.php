<?php

declare(strict_types=1);

namespace Misaf\VendraAddress\Policies;

use Misaf\VendraAddress\Enums\AddressPolicyEnum;
use Misaf\VendraSupport\Concerns\AuthorizesCreateAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesForceDeleteAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesReplicateAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesRestoreAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;
use Misaf\VendraSupport\Concerns\AuthorizesUpdateAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesViewAbilities;
use Misaf\VendraSupport\Concerns\ResolvesPolicyPermissions;

final class AddressPolicy
{
    use AuthorizesCreateAbilities;
    use AuthorizesDeleteAbilities;
    use AuthorizesForceDeleteAbilities;
    use AuthorizesReplicateAbilities;
    use AuthorizesRestoreAbilities;
    use AuthorizesSandboxMode;
    use AuthorizesUpdateAbilities;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return AddressPolicyEnum::class;
    }
}
