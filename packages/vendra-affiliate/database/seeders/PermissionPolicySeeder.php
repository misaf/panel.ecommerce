<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Database\Seeders;

use Misaf\VendraAffiliate\AffiliatePlugin;
use Misaf\VendraAffiliate\Enums\AffiliateClickPolicyEnum;
use Misaf\VendraAffiliate\Enums\AffiliateCommissionPolicyEnum;
use Misaf\VendraAffiliate\Enums\AffiliatePayoutPolicyEnum;
use Misaf\VendraAffiliate\Enums\AffiliatePolicyEnum;
use Misaf\VendraAffiliate\Enums\AffiliateReferralPolicyEnum;
use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = AffiliatePlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(AffiliatePolicyEnum::cases(), 'value'),
            ...array_column(AffiliateClickPolicyEnum::cases(), 'value'),
            ...array_column(AffiliateReferralPolicyEnum::cases(), 'value'),
            ...array_column(AffiliateCommissionPolicyEnum::cases(), 'value'),
            ...array_column(AffiliatePayoutPolicyEnum::cases(), 'value'),
        ];
    }
}
