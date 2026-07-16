<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliateCommissionPolicyEnum: string
{
    case Approve = 'approve-affiliate-commission';
    case Delete = 'delete-affiliate-commission';
    case DeleteAny = 'delete-any-affiliate-commission';
    case Restore = 'restore-affiliate-commission';
    case RestoreAny = 'restore-any-affiliate-commission';
    case Reverse = 'reverse-affiliate-commission';
    case View = 'view-affiliate-commission';
    case ViewAny = 'view-any-affiliate-commission';
}
