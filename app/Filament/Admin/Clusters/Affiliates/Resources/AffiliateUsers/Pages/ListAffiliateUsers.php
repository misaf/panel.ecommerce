<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\Pages;

use App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\AffiliateUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListAffiliateUsers extends ListRecords
{
    protected static string $resource = AffiliateUserResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('navigation.affiliate_user');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
