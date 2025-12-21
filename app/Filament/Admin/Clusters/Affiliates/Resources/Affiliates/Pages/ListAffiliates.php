<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\Pages;

use App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\AffiliateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListAffiliates extends ListRecords
{
    protected static string $resource = AffiliateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('navigation.affiliate');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
