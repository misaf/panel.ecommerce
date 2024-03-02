<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfilePhoneResource\Pages;

use App\Filament\Admin\Resources\User\UserProfilePhoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditUserProfilePhone extends EditRecord
{
    protected static string $resource = UserProfilePhoneResource::class;

    protected function afterSave(): void
    {
        $this->record->setStatus($this->data['status']);
    }

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
