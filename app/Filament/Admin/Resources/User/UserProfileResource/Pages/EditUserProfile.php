<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditUserProfile extends EditRecord
{
    protected static string $resource = UserProfileResource::class;

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
