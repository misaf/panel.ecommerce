<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\Enums;

enum OrderPolicyEnum: string
{
    case Create = 'create-order';
    case Delete = 'delete-order';
    case DeleteAny = 'delete-any-order';
    case ForceDelete = 'force-delete-order';
    case ForceDeleteAny = 'force-delete-any-order';
    case Restore = 'restore-order';
    case RestoreAny = 'restore-any-order';
    case Update = 'update-order';
    case View = 'view-order';
    case ViewAny = 'view-any-order';
}
