<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Enums;

enum CountryPolicyEnum: string
{
    case Create = 'create-country';
    case Delete = 'delete-country';
    case DeleteAny = 'delete-any-country';
    case ForceDelete = 'force-delete-country';
    case ForceDeleteAny = 'force-delete-any-country';
    case Reorder = 'reorder-country';
    case Replicate = 'replicate-country';
    case Restore = 'restore-country';
    case RestoreAny = 'restore-any-country';
    case Update = 'update-country';
    case View = 'view-country';
    case ViewAny = 'view-any-country';
}
