<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\ActivityLogResource\Pages;

use App\Filament\Admin\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
