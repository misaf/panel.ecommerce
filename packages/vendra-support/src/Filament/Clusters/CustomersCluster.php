<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class CustomersCluster extends Cluster
{
    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'customers';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function getNavigationLabel(): string
    {
        return NavigationGroup::Customers->getLabel();
    }

    public static function getClusterBreadcrumb(): string
    {
        return NavigationGroup::Customers->getLabel();
    }
}
