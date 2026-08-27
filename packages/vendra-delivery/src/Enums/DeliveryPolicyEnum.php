<?php

declare(strict_types=1);

namespace Misaf\VendraDelivery\Enums;

enum DeliveryPolicyEnum: string
{
    case Delete = 'delete-delivery';
    case DeleteAny = 'delete-any-delivery';
    case Update = 'update-delivery';
    case View = 'view-delivery';
    case ViewAny = 'view-any-delivery';
}
