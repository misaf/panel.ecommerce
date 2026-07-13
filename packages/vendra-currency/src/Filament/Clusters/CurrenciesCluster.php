<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters;

use Filament\Clusters\Cluster;

final class CurrenciesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'currencies';

    public static function getNavigationGroup(): string
    {
        return __('vendra-currency::navigation.billing_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-currency::navigation.currency');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('vendra-currency::navigation.billing_management');
    }
}
