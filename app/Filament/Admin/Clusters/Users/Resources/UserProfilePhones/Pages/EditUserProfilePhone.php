<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\UserProfilePhoneResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditUserProfilePhone extends EditRecord
{
    protected static string $resource = UserProfilePhoneResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('navigation.user_profile_phone');
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
}
