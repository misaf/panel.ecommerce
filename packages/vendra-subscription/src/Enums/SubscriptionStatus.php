<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Enums;

enum SubscriptionStatus: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
}
