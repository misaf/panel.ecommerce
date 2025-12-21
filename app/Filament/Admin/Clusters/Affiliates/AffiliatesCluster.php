<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates;

use Filament\Clusters\Cluster;

final class AffiliatesCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'affiliates';

    public static function getNavigationGroup(): string
    {
        return 'user_management';
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.affiliate');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.user_management');
    }
}
