<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalCityResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalCityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditGeographicalCity extends EditRecord
{
    protected static string $resource = GeographicalCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['geographical_zone_id'], $data['geographical_country_id']);


        return $data;
    }
}
