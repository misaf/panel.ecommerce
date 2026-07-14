<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class LanguagesCluster extends Cluster
{
    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'languages';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    public static function getNavigationGroup(): string
    {
        return NavigationGroup::Localization->getLabel();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-language::navigation.language');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('vendra-language::navigation.language');
    }
}
