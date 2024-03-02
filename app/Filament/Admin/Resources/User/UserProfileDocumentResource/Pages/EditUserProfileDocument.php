<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileDocumentResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileDocumentResource;
use App\Support\Enums\UserProfileDocumentStatusEnum;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditUserProfileDocument extends EditRecord
{
    protected static string $resource = UserProfileDocumentResource::class;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] === UserProfileDocumentStatusEnum::Approved->value) {
            $data['verified_at'] = now();
        }

        return $data;
    }
}
