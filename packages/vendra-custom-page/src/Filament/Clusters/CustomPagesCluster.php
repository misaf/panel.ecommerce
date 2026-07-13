<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Filament\Clusters;

use Filament\Clusters\Cluster;

final class CustomPagesCluster extends Cluster
{
    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'custom-pages';

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.content_management');
    }
}
