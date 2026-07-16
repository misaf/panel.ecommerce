<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Policies;

use Misaf\VendraLanguage\Enums\LanguageLinePolicyEnum;
use Misaf\VendraSupport\Concerns\AuthorizesCreateAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesDeleteAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesReplicateAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesSandboxMode;
use Misaf\VendraSupport\Concerns\AuthorizesUpdateAbilities;
use Misaf\VendraSupport\Concerns\AuthorizesViewAbilities;
use Misaf\VendraSupport\Concerns\ResolvesPolicyPermissions;

final class LanguageLinePolicy
{
    use AuthorizesCreateAbilities;
    use AuthorizesDeleteAbilities;
    use AuthorizesReplicateAbilities;
    use AuthorizesSandboxMode;
    use AuthorizesUpdateAbilities;
    use AuthorizesViewAbilities;
    use ResolvesPolicyPermissions;

    protected static function permissionEnum(): string
    {
        return LanguageLinePolicyEnum::class;
    }
}
