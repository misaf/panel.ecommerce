<?php

declare(strict_types=1);

namespace Misaf\VendraAddress\Enums;

enum AddressPolicyEnum: string
{
    case Create = 'create-address';
    case Delete = 'delete-address';
    case DeleteAny = 'delete-any-address';
    case ForceDelete = 'force-delete-address';
    case ForceDeleteAny = 'force-delete-any-address';
    case Replicate = 'replicate-address';
    case Restore = 'restore-address';
    case RestoreAny = 'restore-any-address';
    case Update = 'update-address';
    case View = 'view-address';
    case ViewAny = 'view-any-address';
}
