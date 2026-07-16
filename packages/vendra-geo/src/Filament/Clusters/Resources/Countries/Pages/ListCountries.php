<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\CountryResource;

final class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('vendra-geo::navigation.country');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
