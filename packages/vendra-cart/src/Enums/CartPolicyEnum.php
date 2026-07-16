<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Enums;

enum CartPolicyEnum: string
{
    case Delete = 'delete-cart';
    case DeleteAny = 'delete-any-cart';
    case View = 'view-cart';
    case ViewAny = 'view-any-cart';
}
