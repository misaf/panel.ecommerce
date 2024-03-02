<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalCountryResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewGeographicalCountry extends ViewRecord
{
    protected static string $resource = GeographicalCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
