<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission\PermissionResource\Pages;

use App\Filament\Admin\Resources\Permission\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
