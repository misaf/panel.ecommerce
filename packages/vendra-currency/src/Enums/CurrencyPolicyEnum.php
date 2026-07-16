<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Enums;

enum CurrencyPolicyEnum: string
{
    case Create = 'create-currency';
    case Delete = 'delete-currency';
    case DeleteAny = 'delete-any-currency';
    case ForceDelete = 'force-delete-currency';
    case ForceDeleteAny = 'force-delete-any-currency';
    case Reorder = 'reorder-currency';
    case Replicate = 'replicate-currency';
    case Restore = 'restore-currency';
    case RestoreAny = 'restore-any-currency';
    case Update = 'update-currency';
    case View = 'view-currency';
    case ViewAny = 'view-any-currency';
}
