<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages;

use Filament\Clusters\Cluster;

final class LanguagesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'languages';

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('language::navigation.language');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.content_management');
    }
}
