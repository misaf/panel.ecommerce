<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission\RoleResource\Pages;

use App\Filament\Admin\Resources\Permission\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
