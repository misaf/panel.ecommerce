<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\UserLevelHistoryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUserLevelHistory extends CreateRecord
{
    protected static string $resource = UserLevelHistoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('user-level::navigation.user_level_history');
    }
}
