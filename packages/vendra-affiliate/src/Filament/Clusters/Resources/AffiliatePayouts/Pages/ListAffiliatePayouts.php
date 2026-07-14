<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Pages;

use Filament\Resources\Pages\ListRecords;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\AffiliatePayoutResource;

final class ListAffiliatePayouts extends ListRecords
{
    protected static string $resource = AffiliatePayoutResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('vendra-affiliate::navigation.affiliate_payout');
    }
}
