<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

enum AffiliateCommissionPolicyEnum: string
{
    case APPROVE = 'approve-affiliate-commission';
    case DELETE = 'delete-affiliate-commission';
    case DELETE_ANY = 'delete-any-affiliate-commission';
    case RESTORE = 'restore-affiliate-commission';
    case RESTORE_ANY = 'restore-any-affiliate-commission';
    case REVERSE = 'reverse-affiliate-commission';
    case VIEW = 'view-affiliate-commission';
    case VIEW_ANY = 'view-any-affiliate-commission';
}
