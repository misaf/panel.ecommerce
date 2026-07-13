<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers\Pages;

use Filament\Resources\Pages\CreateRecord;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers\AffiliateUserResource;

final class CreateAffiliateUser extends CreateRecord
{
    protected static string $resource = AffiliateUserResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('vendra-affiliate::navigation.affiliate_user');
    }
}
