<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalStateResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalStateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditGeographicalState extends EditRecord
{
    protected static string $resource = GeographicalStateResource::class;

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
        unset($data['geographical_zone_id']);

        return $data;
    }
}
