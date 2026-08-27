<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\Enums;

enum OrderLinePolicyEnum: string
{
    case View = 'view-order-line';
    case ViewAny = 'view-any-order-line';
}
