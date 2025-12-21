<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\UserProfileDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Misaf\User\Enums\UserProfileDocumentStatusEnum;

final class EditUserProfileDocument extends EditRecord
{
    protected static string $resource = UserProfileDocumentResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('navigation.user_profile_document');
    }

    protected function afterSave(): void
    {
        $this->record->setStatus($this->data['status']);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
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
