<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class SalesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'sales';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function getNavigationLabel(): string
    {
        return NavigationGroup::Sales->getLabel();
    }

    public static function getClusterBreadcrumb(): string
    {
        return NavigationGroup::Sales->getLabel();
    }
}
