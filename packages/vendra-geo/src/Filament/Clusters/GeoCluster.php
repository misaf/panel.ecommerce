<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class GeoCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'geo';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    public static function getNavigationGroup(): string
    {
        return NavigationGroup::Localization->getLabel();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-geo::navigation.geo');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('vendra-geo::navigation.geo');
    }
}
