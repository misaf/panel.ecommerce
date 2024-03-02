<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalCityResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalCityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListGeographicalCity extends ListRecords
{
    protected static string $resource = GeographicalCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
