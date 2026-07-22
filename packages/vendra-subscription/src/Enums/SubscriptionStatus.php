<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Enums;

enum SubscriptionStatus: string
{
    case PendingPayment = 'pending_payment';
    case Active = 'active';
    case PastDue = 'past_due';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
}
