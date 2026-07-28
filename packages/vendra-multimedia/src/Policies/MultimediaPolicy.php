<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia\Policies;

use Misaf\VendraMultimedia\Enums\MultimediaPolicyEnum;
use Misaf\VendraSupport\Authorization\AuthorizesCreateAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesForceDeleteAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesRestoreAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesSandboxMode;
use Misaf\VendraSupport\Authorization\AuthorizesUpdateAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesViewAbilities;
use Misaf\VendraSupport\Authorization\ResolvesPolicyPermissions;

final class MultimediaPolicy
{
    use AuthorizesCreateAbilities;
    use AuthorizesDeleteAbilities;
    use AuthorizesForceDeleteAbilities;
    use AuthorizesRestoreAbilities;
    use AuthorizesSandboxMode;
    use AuthorizesUpdateAbilities;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return MultimediaPolicyEnum::class;
    }
}
