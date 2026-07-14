<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliateReferralPolicyEnum: string
{
    case CREATE = 'create-affiliate-referral';
    case DELETE = 'delete-affiliate-referral';
    case DELETE_ANY = 'delete-any-affiliate-referral';
    case VIEW = 'view-affiliate-referral';
    case VIEW_ANY = 'view-any-affiliate-referral';
}
