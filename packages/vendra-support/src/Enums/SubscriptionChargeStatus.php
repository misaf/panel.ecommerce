<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Enums;

enum SubscriptionChargeStatus: string
{
    case Processing = 'processing';
    case RequiresAction = 'requires_action';
    case Paid = 'paid';
    case Failed = 'failed';
}
