<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters;

use Filament\Clusters\Cluster;

final class NewslettersCluster extends Cluster
{
    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'newsletters';

    public static function getNavigationGroup(): string
    {
        return 'content_management';
    }

    public static function getNavigationLabel(): string
    {
        return __('newsletter/navigation.newsletter');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.content_management');
    }
}
