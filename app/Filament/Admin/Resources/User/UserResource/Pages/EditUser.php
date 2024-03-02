<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserResource\Pages;

use App\Filament\Admin\Resources\User\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
