<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class CustomPagesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'custom-pages';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getNavigationGroup(): string
    {
        return NavigationGroup::Content->getLabel();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }
}
