<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class LocalizationCluster extends Cluster
{
    protected static ?int $navigationSort = 6;

    protected static ?string $slug = 'localization';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    public static function getNavigationLabel(): string
    {
        return NavigationGroup::Localization->getLabel();
    }

    public static function getClusterBreadcrumb(): string
    {
        return NavigationGroup::Localization->getLabel();
    }
}
