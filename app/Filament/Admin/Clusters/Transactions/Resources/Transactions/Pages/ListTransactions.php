<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Pages;

use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\TransactionResource;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionBonusOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionCommissionOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionDepositOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionTransferOverviewWidget;
use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Widgets\TransactionWithdrawalOverviewWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Database\Eloquent\Builder;
use Misaf\Transaction\Enums\TransactionTypeEnum;
use Misaf\Transaction\Models\Transaction;

final class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('navigation.transaction');
    }

    /**
     * @return array<string|int, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(Transaction::count()),

            TransactionTypeEnum::Deposit->value => Tab::make()
                ->badge(Transaction::deposit()->count())
                ->label(TransactionTypeEnum::Deposit->getLabel())
                ->modifyQueryUsing(fn(Builder $query) => $query->deposit()),

            TransactionTypeEnum::Withdrawal->value => Tab::make()
                ->badge(Transaction::withdrawal()->count())
                ->label(TransactionTypeEnum::Withdrawal->getLabel())
                ->modifyQueryUsing(fn(Builder $query) => $query->withdrawal()),

            TransactionTypeEnum::Commission->value => Tab::make()
                ->badge(Transaction::commission()->count())
                ->label(TransactionTypeEnum::Commission->getLabel())
                ->modifyQueryUsing(fn(Builder $query) => $query->commission()),

            TransactionTypeEnum::Transfer->value => Tab::make()
                ->badge(Transaction::transfer()->count())
                ->label(TransactionTypeEnum::Transfer->getLabel())
                ->modifyQueryUsing(fn(Builder $query) => $query->transfer()),

            TransactionTypeEnum::Bonus->value => Tab::make()
                ->badge(Transaction::bonus()->count())
                ->label(TransactionTypeEnum::Bonus->getLabel())
                ->modifyQueryUsing(fn(Builder $query) => $query->bonus()),
        ];
    }

    /**
     * @return array<class-string<Widget>|WidgetConfiguration>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
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
            TransactionDepositOverviewWidget::class,
            TransactionWithdrawalOverviewWidget::class,
            TransactionCommissionOverviewWidget::class,
            TransactionBonusOverviewWidget::class,
            TransactionTransferOverviewWidget::class,
        ];
    }
}
