<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters;

use Filament\Clusters\Cluster;
use Misaf\VendraAttribute\AttributePlugin;

final class AttributesCluster extends Cluster
{
    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'attributes';

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
