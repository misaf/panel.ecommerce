<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\CountryResource;

final class EditCountry extends EditRecord
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
