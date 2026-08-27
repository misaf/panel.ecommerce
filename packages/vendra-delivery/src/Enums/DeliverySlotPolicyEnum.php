<?php

declare(strict_types=1);

namespace Misaf\VendraDelivery\Enums;

enum DeliverySlotPolicyEnum: string
{
    case Create = 'create-delivery-slot';
    case Delete = 'delete-delivery-slot';
    case DeleteAny = 'delete-any-delivery-slot';
    case ForceDelete = 'force-delete-delivery-slot';
    case ForceDeleteAny = 'force-delete-any-delivery-slot';
    case Reorder = 'reorder-delivery-slot';
    case Restore = 'restore-delivery-slot';
    case RestoreAny = 'restore-any-delivery-slot';
    case Update = 'update-delivery-slot';
    case View = 'view-delivery-slot';
    case ViewAny = 'view-any-delivery-slot';
}
