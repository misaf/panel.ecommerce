<?php

declare(strict_types=1);

namespace App\Filament\User\Clusters\Billings\Deposit\Pages;

use App\Filament\User\Clusters\Billings\Deposit\DepositCluster\DepositCluster;
use Filament\Clusters\Cluster;
use Filament\Pages\Page;
use Misaf\VendraTransaction\Facades\TransactionService;
use Misaf\VendraTransaction\Models\TransactionGateway;

final class Coinpayments extends Page
{
    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = DepositCluster::class;

    protected string $view = 'filament.user.pages.billings.coinpayments.deposit';

    protected static ?string $slug = 'coinpayments';

    public static function getNavigationGroup(): string
    {
        return __('navigation.my_deposit');
    }

    public static function getNavigationLabel(): string
    {
        $name = TransactionGateway::whereJsonContainsLocale('slug', app()->getLocale(), 'coinpayments', '=')
            ->value('name');

        return is_string($name) ? $name : __('payment.coinpayments');
    }

    public static function getNavigationSort(): int
    {
        $position = TransactionGateway::whereJsonContainsLocale('slug', app()->getLocale(), 'coinpayments', '=')
            ->value('position');

        return is_numeric($position) ? (int) $position : 0;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return TransactionService::hasActiveTransactionGateway('coinpayments');
    }

    public static function canAccess(): bool
    {
        return TransactionService::hasActiveTransactionGateway('coinpayments');
    }
}
