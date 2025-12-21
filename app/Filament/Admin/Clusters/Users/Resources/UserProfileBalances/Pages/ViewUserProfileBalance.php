<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\UserProfileBalanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewUserProfileBalance extends ViewRecord
{
    protected static string $resource = UserProfileBalanceResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.user_profile_balance');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
