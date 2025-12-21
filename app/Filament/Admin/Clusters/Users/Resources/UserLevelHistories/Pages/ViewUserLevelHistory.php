<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\UserLevelHistoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewUserLevelHistory extends ViewRecord
{
    protected static string $resource = UserLevelHistoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('user-level::navigation.user_level_history');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
