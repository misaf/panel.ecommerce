<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters;

use Filament\Clusters\Cluster;

final class AffiliatesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'affiliates';

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.user_management');
    }
}
