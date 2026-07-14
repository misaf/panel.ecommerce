<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraAttribute\AttributePlugin;

final class AttributesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'attributes';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    public static function getNavigationGroup(): string
    {
        return AttributePlugin::make()->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return trans_choice('vendra-attribute::navigation.attribute', 1);
    }

    public static function getClusterBreadcrumb(): string
    {
        return trans_choice('vendra-attribute::navigation.attribute_management', 1);
    }
}
