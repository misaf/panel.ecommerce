<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateUsers\AffiliateUserResource;

final class ViewAffiliateUser extends ViewRecord
{
    protected static string $resource = AffiliateUserResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('vendra-affiliate::navigation.affiliate_user');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
