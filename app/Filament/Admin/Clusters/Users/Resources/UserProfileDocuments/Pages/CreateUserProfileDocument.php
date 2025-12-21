<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\UserProfileDocumentResource;
use Filament\Resources\Pages\CreateRecord;
use Misaf\User\Enums\UserProfileDocumentStatusEnum;

final class CreateUserProfileDocument extends CreateRecord
{
    protected static string $resource = UserProfileDocumentResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.user_profile_document');
    }

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
