<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters;

use Filament\Clusters\Cluster;
use Misaf\VendraAffiliate\AffiliatePlugin;

final class AffiliatesCluster extends Cluster
{
    protected static ?int $navigationSort = 6;

    protected static ?string $slug = 'affiliates';

    public static function getNavigationGroup(): string
    {
        return AffiliatePlugin::get()->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getClusterBreadcrumb(): string
    {
        return AffiliatePlugin::get()->getNavigationGroup();
    }
}
