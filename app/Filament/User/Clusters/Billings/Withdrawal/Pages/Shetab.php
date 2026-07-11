<?php

declare(strict_types=1);

namespace App\Filament\User\Clusters\Billings\Withdrawal\Pages;

use App\Filament\User\Clusters\Billings\Withdrawal\WithdrawalCluster\WithdrawalCluster;
use Filament\Clusters\Cluster;
use Filament\Pages\Page;
use Misaf\VendraTransaction\Models\TransactionGateway;

final class Shetab extends Page
{
    protected string $view = 'filament.user.pages.billings.shetab.withdrawal';

    protected static ?string $slug = 'shetab';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = WithdrawalCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('payment.shetab');
    }

    public static function getModelLabel(): string
    {
        return __('payment.shetab');
    }

    public static function getNavigationLabel(): string
    {
        return __('payment.shetab');
    }

    public static function getPluralModelLabel(): string
    {
        return __('payment.shetab');
    }

    public static function getNavigationSort(): int
    {
        $position = TransactionGateway::whereJsonContainsLocale('slug', app()->getLocale(), 'shetab', '=')
            ->value('position');

        return is_numeric($position) ? (int) $position : 0;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return false;
    }
}
