<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraAffiliate\AffiliatePlugin;

final class AffiliatesCluster extends Cluster
{
    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'affiliates';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

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
