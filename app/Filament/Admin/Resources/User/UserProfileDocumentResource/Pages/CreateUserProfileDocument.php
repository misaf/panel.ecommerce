<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileDocumentResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileDocumentResource;
use App\Support\Enums\UserProfileDocumentStatusEnum;
use Filament\Resources\Pages\CreateRecord;

final class CreateUserProfileDocument extends CreateRecord
{
    protected static string $resource = UserProfileDocumentResource::class;

    protected function afterCreate(): void
    {
        $this->record->setStatus($this->data['status']);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['status'] === UserProfileDocumentStatusEnum::Approved->value) {
            $data['verified_at'] = now();
        }

        return $data;
    }
}
