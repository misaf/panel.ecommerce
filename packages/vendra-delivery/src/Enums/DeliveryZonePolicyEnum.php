<?php

declare(strict_types=1);

namespace Misaf\VendraDelivery\Enums;

enum DeliveryZonePolicyEnum: string
{
    case Create = 'create-delivery-zone';
    case Delete = 'delete-delivery-zone';
    case DeleteAny = 'delete-any-delivery-zone';
    case ForceDelete = 'force-delete-delivery-zone';
    case ForceDeleteAny = 'force-delete-any-delivery-zone';
    case Reorder = 'reorder-delivery-zone';
    case Restore = 'restore-delivery-zone';
    case RestoreAny = 'restore-any-delivery-zone';
    case Update = 'update-delivery-zone';
    case View = 'view-delivery-zone';
    case ViewAny = 'view-any-delivery-zone';
}
