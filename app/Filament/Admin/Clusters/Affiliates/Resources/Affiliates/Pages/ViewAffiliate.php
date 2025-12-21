<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\Pages;

use App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\AffiliateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewAffiliate extends ViewRecord
{
    protected static string $resource = AffiliateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.affiliate');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
