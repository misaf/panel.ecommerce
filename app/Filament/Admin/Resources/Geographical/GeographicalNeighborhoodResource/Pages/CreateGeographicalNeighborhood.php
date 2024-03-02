<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalNeighborhoodResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalNeighborhoodResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGeographicalNeighborhood extends CreateRecord
{
    protected static string $resource = GeographicalNeighborhoodResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['geographical_zone_id'], $data['geographical_country_id'], $data['geographical_state_id']);



        return $data;
    }
}
