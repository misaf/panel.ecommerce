<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalZoneResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditGeographicalZone extends EditRecord
{
    protected static string $resource = GeographicalZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
