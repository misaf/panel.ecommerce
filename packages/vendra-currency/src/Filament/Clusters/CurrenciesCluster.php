<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class CurrenciesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'currencies';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    public static function getNavigationGroup(): string
    {
        return NavigationGroup::Sales->getLabel();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-currency::navigation.currency');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('vendra-currency::navigation.currency');
    }
}
