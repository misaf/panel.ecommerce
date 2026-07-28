<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Policies;

use Misaf\VendraLanguage\Enums\LanguageLinePolicyEnum;
use Misaf\VendraSupport\Authorization\AuthorizesCreateAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesSandboxMode;
use Misaf\VendraSupport\Authorization\AuthorizesUpdateAbilities;
use Misaf\VendraSupport\Authorization\AuthorizesViewAbilities;
use Misaf\VendraSupport\Authorization\ResolvesPolicyPermissions;

final class LanguageLinePolicy
{
    use AuthorizesCreateAbilities;
    use AuthorizesDeleteAbilities;
    use AuthorizesSandboxMode;
    use AuthorizesUpdateAbilities;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return LanguageLinePolicyEnum::class;
    }
}
