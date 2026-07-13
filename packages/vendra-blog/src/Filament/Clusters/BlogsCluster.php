<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Filament\Clusters;

use Filament\Clusters\Cluster;
use Misaf\VendraBlog\BlogPlugin;

final class BlogsCluster extends Cluster
{
    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'blogs';

    public static function getNavigationGroup(): string
    {
        return BlogPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-blog::navigation.blog');
    }

    public static function getClusterBreadcrumb(): string
    {
        return BlogPlugin::get()->getNavigationGroup();
    }
}
