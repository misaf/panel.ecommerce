<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalStateResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalStateResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGeographicalState extends CreateRecord
{
    protected static string $resource = GeographicalStateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['geographical_zone_id']);

        return $data;
    }
}
