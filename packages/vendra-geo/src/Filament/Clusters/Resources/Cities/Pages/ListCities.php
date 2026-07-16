<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\CityResource;

final class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
