<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\CityResource;

final class ViewCity extends ViewRecord
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
