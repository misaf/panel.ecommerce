<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalNeighborhoodResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalNeighborhoodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditGeographicalNeighborhood extends EditRecord
{
    protected static string $resource = GeographicalNeighborhoodResource::class;

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
        unset($data['geographical_zone_id'], $data['geographical_country_id'], $data['geographical_state_id']);



        return $data;
    }
}
