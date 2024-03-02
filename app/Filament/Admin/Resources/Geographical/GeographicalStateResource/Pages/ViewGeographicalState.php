<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalStateResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalStateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewGeographicalState extends ViewRecord
{
    protected static string $resource = GeographicalStateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
