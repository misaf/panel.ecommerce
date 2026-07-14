<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraBlog\BlogPlugin;

final class BlogsCluster extends Cluster
{
    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'blogs';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

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
