<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class CatalogCluster extends Cluster
{
    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'catalog';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    public static function getNavigationLabel(): string
    {
        return NavigationGroup::Catalog->getLabel();
    }

    public static function getClusterBreadcrumb(): string
    {
        return NavigationGroup::Catalog->getLabel();
    }
}
