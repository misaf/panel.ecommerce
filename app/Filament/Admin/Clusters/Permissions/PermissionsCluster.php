<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Permissions;

use Filament\Clusters\Cluster;

final class PermissionsCluster extends Cluster
{
    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'permissions';

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.permission');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.user_management');
    }
}
