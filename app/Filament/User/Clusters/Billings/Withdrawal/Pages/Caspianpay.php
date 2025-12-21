<?php

declare(strict_types=1);

namespace App\Filament\User\Clusters\Billings\Withdrawal\Pages;

use App\Filament\User\Clusters\Billings\Withdrawal\WithdrawalCluster\WithdrawalCluster;
use App\Livewire\User\Payment\Caspianpay\Widgets\WithdrawalOverview;
use Filament\Clusters\Cluster;
use Filament\Pages\Page;
use Misaf\Transaction\Facades\TransactionService;
use Misaf\Transaction\Models\TransactionGateway;

final class Caspianpay extends Page
{
    protected string $view = 'filament.user.pages.billings.caspianpay.withdrawal';

    protected static ?string $slug = 'caspianpay';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = WithdrawalCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('caspianpay::navigation.caspianpay');
    }

    public static function getModelLabel(): string
    {
        return __('caspianpay::navigation.caspianpay');
    }

    public static function getNavigationLabel(): string
    {
        return __('caspianpay::navigation.caspianpay');
    }

    public static function getPluralModelLabel(): string
    {
        return __('caspianpay::navigation.caspianpay');
    }

    public static function getNavigationSort(): ?int
    {
        $position = TransactionGateway::whereJsonContainsLocale('slug', app()->getLocale(), 'caspianpay', '=')
            ->value('position');

        return is_numeric($position) ? (int) $position : 0;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return TransactionService::hasActiveTransactionGateway('caspianpay');
    }

    public static function canAccess(): bool
    {
        return TransactionService::hasActiveTransactionGateway('caspianpay');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // WithdrawalOverview::class,
        ];
    }
}
