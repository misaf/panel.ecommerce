<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Enums;

enum AttributePolicyEnum: string
{
    case Create = 'create-attribute';
    case Delete = 'delete-attribute';
    case DeleteAny = 'delete-any-attribute';
    case ForceDelete = 'force-delete-attribute';
    case ForceDeleteAny = 'force-delete-any-attribute';
    case Reorder = 'reorder-attribute';
    case Replicate = 'replicate-attribute';
    case Restore = 'restore-attribute';
    case RestoreAny = 'restore-any-attribute';
    case Update = 'update-attribute';
    case View = 'view-attribute';
    case ViewAny = 'view-any-attribute';
}
