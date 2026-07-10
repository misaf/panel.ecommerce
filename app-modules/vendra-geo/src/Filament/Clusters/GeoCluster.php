<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters;

use Filament\Clusters\Cluster;

final class GeoCluster extends Cluster
{
    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'geo';

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-geo::navigation.geo');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.content_management');
    }
}
