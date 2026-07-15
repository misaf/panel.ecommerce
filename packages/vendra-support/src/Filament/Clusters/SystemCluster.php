<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class SystemCluster extends Cluster
{
    protected static ?int $navigationSort = 7;

    protected static ?string $slug = 'system';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function getNavigationLabel(): string
    {
        return NavigationGroup::System->getLabel();
    }

    public static function getClusterBreadcrumb(): string
    {
        return NavigationGroup::System->getLabel();
    }
}
