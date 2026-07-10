<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Enums;

enum StatePolicyEnum: string
{
    case CREATE = 'create-state';
    case DELETE = 'delete-state';
    case DELETE_ANY = 'delete-any-state';
    case FORCE_DELETE = 'force-delete-state';
    case FORCE_DELETE_ANY = 'force-delete-any-state';
    case REORDER = 'reorder-state';
    case REPLICATE = 'replicate-state';
    case RESTORE = 'restore-state';
    case RESTORE_ANY = 'restore-any-state';
    case UPDATE = 'update-state';
    case VIEW = 'view-state';
    case VIEW_ANY = 'view-any-state';
}
