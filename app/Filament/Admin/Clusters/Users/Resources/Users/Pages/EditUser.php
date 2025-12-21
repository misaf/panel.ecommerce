<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\Users\Pages;

use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionBonusOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionCommissionOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionDepositOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionLimitOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionWithdrawalOverviewWidget;
use App\Filament\Admin\Clusters\Users\Resources\UserLevels\Widgets\UserLevelOverviewWidget;
use App\Filament\Admin\Clusters\Users\Resources\UserRakeResource\Widgets\UserRakeOverviewWidget;
use App\Filament\Admin\Clusters\Users\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('navigation.user');
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * @return array<string, int>
     */
    public function getHeaderWidgetsColumns(): array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
        ];
    }

    /**
     * @return array<class-string<Widget>|WidgetConfiguration>
     */
    protected function getHeaderWidgets(): array
    {
        return [
            UserLevelOverviewWidget::class,
            UserRakeOverviewWidget::class,
            TransactionDepositOverviewWidget::class,
            TransactionWithdrawalOverviewWidget::class,
            TransactionBonusOverviewWidget::class,
            TransactionCommissionOverviewWidget::class,
            TransactionLimitOverviewWidget::class,
        ];
    }
}
