<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliateReferralPolicyEnum: string
{
    case Create = 'create-affiliate-referral';
    case Delete = 'delete-affiliate-referral';
    case DeleteAny = 'delete-any-affiliate-referral';
    case View = 'view-affiliate-referral';
    case ViewAny = 'view-any-affiliate-referral';
}
