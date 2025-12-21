<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\UserProfilePhoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListUserProfilePhones extends ListRecords
{
    protected static string $resource = UserProfilePhoneResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('navigation.user_profile_phone');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
