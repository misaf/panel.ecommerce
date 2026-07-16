<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\States\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\StateResource;

final class ViewState extends ViewRecord
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
