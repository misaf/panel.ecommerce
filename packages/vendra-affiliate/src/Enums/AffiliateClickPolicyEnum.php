<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliateClickPolicyEnum: string
{
    case Delete = 'delete-affiliate-click';
    case DeleteAny = 'delete-any-affiliate-click';
    case View = 'view-affiliate-click';
    case ViewAny = 'view-any-affiliate-click';
}
