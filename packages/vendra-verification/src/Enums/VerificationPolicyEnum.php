<?php

declare(strict_types=1);

namespace Misaf\VendraVerification\Enums;

enum VerificationPolicyEnum: string
{
    case Create = 'create-verification';
    case Delete = 'delete-verification';
    case DeleteAny = 'delete-any-verification';
    case ForceDelete = 'force-delete-verification';
    case ForceDeleteAny = 'force-delete-any-verification';
    case Restore = 'restore-verification';
    case RestoreAny = 'restore-any-verification';
    case Update = 'update-verification';
    case View = 'view-verification';
    case ViewAny = 'view-any-verification';
}
