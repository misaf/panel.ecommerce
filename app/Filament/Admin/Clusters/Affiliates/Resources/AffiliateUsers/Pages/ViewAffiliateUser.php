<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\Pages;

use App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\AffiliateUserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewAffiliateUser extends ViewRecord
{
    protected static string $resource = AffiliateUserResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.affiliate_user');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
