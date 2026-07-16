<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliatePayoutPolicyEnum: string
{
    case Process = 'process-affiliate-payout';
    case View = 'view-affiliate-payout';
    case ViewAny = 'view-any-affiliate-payout';
}
