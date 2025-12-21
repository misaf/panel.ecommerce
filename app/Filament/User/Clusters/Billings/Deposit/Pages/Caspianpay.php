<?php

declare(strict_types=1);

namespace App\Filament\User\Clusters\Billings\Deposit\Pages;

use App\Filament\User\Clusters\Billings\Deposit\DepositCluster\DepositCluster;
use Filament\Clusters\Cluster;
use Filament\Pages\Page;
use Misaf\Transaction\Facades\TransactionService;
use Misaf\Transaction\Models\TransactionGateway;

final class Caspianpay extends Page
{
    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = DepositCluster::class;

    protected string $view = 'filament.user.pages.billings.caspianpay.deposit';

    protected static ?string $slug = 'caspianpay';

    public static function getNavigationGroup(): string
    {
        return __('navigation.my_deposit');
    }

    public static function getNavigationLabel(): string
    {
        $name = TransactionGateway::whereJsonContainsLocale('slug', app()->getLocale(), 'caspianpay', '=')
            ->value('name');

        return is_string($name) ? $name : __('Caspianpay');
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
}
