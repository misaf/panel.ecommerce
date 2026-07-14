<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliatePolicyEnum: string
{
    case CREATE = 'create-affiliate';
    case DELETE = 'delete-affiliate';
    case DELETE_ANY = 'delete-any-affiliate';
    case FORCE_DELETE = 'force-delete-affiliate';
    case FORCE_DELETE_ANY = 'force-delete-any-affiliate';
    case REPLICATE = 'replicate-affiliate';
    case RESTORE = 'restore-affiliate';
    case RESTORE_ANY = 'restore-any-affiliate';
    case UPDATE = 'update-affiliate';
    case VIEW = 'view-affiliate';
    case VIEW_ANY = 'view-any-affiliate';
}
