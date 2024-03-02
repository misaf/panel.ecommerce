<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission\RoleResource\Pages;

use App\Filament\Admin\Resources\Permission\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

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
