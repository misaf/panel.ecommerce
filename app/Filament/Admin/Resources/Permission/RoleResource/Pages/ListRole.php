<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission\RoleResource\Pages;

use App\Filament\Admin\Resources\Permission\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListRole extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
