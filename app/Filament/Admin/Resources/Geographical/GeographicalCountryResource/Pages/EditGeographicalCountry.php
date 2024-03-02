<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalCountryResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditGeographicalCountry extends EditRecord
{
    protected static string $resource = GeographicalCountryResource::class;

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
