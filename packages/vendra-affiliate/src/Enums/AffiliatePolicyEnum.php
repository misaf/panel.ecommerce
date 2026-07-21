<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliatePolicyEnum: string
{
    case Create = 'create-affiliate';
    case Delete = 'delete-affiliate';
    case DeleteAny = 'delete-any-affiliate';
    case ForceDelete = 'force-delete-affiliate';
    case ForceDeleteAny = 'force-delete-any-affiliate';
    case Restore = 'restore-affiliate';
    case RestoreAny = 'restore-any-affiliate';
    case Update = 'update-affiliate';
    case View = 'view-affiliate';
    case ViewAny = 'view-any-affiliate';
}
