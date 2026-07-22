<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\Pages;

use App\Filament\Console\Resources\Properties\PropertyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
