<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalZoneResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListGeographicalZone extends ListRecords
{
    protected static string $resource = GeographicalZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
