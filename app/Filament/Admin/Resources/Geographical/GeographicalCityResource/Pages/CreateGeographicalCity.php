<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalCityResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalCityResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGeographicalCity extends CreateRecord
{
    protected static string $resource = GeographicalCityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['geographical_zone_id'], $data['geographical_country_id']);


        return $data;
    }
}
