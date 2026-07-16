<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Enums;

enum CartItemPolicyEnum: string
{
    case Delete = 'delete-cart-item';
    case DeleteAny = 'delete-any-cart-item';
    case View = 'view-cart-item';
    case ViewAny = 'view-any-cart-item';
}
