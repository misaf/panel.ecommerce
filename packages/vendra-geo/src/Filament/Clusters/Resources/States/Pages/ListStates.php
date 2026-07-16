<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\States\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\StateResource;

final class ListStates extends ListRecords
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
