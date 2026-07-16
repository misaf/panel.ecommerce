<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\CountryResource;

final class ViewCountry extends ViewRecord
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
