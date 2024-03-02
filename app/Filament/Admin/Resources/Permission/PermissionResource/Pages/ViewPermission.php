<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission\PermissionResource\Pages;

use App\Filament\Admin\Resources\Permission\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewPermission extends ViewRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
