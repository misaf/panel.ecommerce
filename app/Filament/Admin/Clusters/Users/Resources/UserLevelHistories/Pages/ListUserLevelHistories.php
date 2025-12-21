<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\UserLevelHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListUserLevelHistories extends ListRecords
{
    protected static string $resource = UserLevelHistoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('user-level::navigation.user_level_history');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
