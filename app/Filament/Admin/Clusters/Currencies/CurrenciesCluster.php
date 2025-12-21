<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies;

use Filament\Clusters\Cluster;

final class CurrenciesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'currencies';

    public static function getNavigationGroup(): string
    {
        return 'billing_management';
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.currency');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.billing_management');
    }
}
