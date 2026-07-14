<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliateClickPolicyEnum: string
{
    case DELETE = 'delete-affiliate-click';
    case DELETE_ANY = 'delete-any-affiliate-click';
    case VIEW = 'view-affiliate-click';
    case VIEW_ANY = 'view-any-affiliate-click';
}
