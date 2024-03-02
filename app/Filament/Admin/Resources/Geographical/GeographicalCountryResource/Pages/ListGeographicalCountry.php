<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalCountryResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListGeographicalCountry extends ListRecords
{
    protected static string $resource = GeographicalCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
