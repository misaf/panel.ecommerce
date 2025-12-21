<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfiles\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfiles\UserProfileResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUserProfile extends CreateRecord
{
    protected static string $resource = UserProfileResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.user_profile');
    }
}
