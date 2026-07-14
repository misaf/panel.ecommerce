<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliatePayoutPolicyEnum: string
{
    case PROCESS = 'process-affiliate-payout';
    case VIEW = 'view-affiliate-payout';
    case VIEW_ANY = 'view-any-affiliate-payout';
}
