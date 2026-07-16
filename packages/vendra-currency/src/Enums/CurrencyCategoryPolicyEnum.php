<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Enums;

enum CurrencyCategoryPolicyEnum: string
{
    case Create = 'create-currency-category';
    case Delete = 'delete-currency-category';
    case DeleteAny = 'delete-any-currency-category';
    case ForceDelete = 'force-delete-currency-category';
    case ForceDeleteAny = 'force-delete-any-currency-category';
    case Reorder = 'reorder-currency-category';
    case Replicate = 'replicate-currency-category';
    case Restore = 'restore-currency-category';
    case RestoreAny = 'restore-any-currency-category';
    case Update = 'update-currency-category';
    case View = 'view-currency-category';
    case ViewAny = 'view-any-currency-category';
}
