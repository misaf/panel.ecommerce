<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Enums;

enum StatePolicyEnum: string
{
    case Create = 'create-state';
    case Delete = 'delete-state';
    case DeleteAny = 'delete-any-state';
    case ForceDelete = 'force-delete-state';
    case ForceDeleteAny = 'force-delete-any-state';
    case Reorder = 'reorder-state';
    case Replicate = 'replicate-state';
    case Restore = 'restore-state';
    case RestoreAny = 'restore-any-state';
    case Update = 'update-state';
    case View = 'view-state';
    case ViewAny = 'view-any-state';
}
