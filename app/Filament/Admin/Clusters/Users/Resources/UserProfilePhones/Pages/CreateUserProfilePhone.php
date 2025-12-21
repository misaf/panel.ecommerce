<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\UserProfilePhoneResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUserProfilePhone extends CreateRecord
{
    protected static string $resource = UserProfilePhoneResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.user_profile_phone');
    }

    protected function afterCreate(): void
    {
        $this->record->setStatus($this->data['status']);
    }
}
