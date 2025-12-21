<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevels\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserLevels\UserLevelResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUserLevel extends CreateRecord
{
    protected static string $resource = UserLevelResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('user-level::navigation.user_level');
    }
}
