<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalNeighborhoodResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalNeighborhoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListGeographicalNeighborhoods extends ListRecords
{
    protected static string $resource = GeographicalNeighborhoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
