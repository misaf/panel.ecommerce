<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Enums;

enum CityPolicyEnum: string
{
    case Create = 'create-city';
    case Delete = 'delete-city';
    case DeleteAny = 'delete-any-city';
    case ForceDelete = 'force-delete-city';
    case ForceDeleteAny = 'force-delete-any-city';
    case Reorder = 'reorder-city';
    case Replicate = 'replicate-city';
    case Restore = 'restore-city';
    case RestoreAny = 'restore-any-city';
    case Update = 'update-city';
    case View = 'view-city';
    case ViewAny = 'view-any-city';
}
