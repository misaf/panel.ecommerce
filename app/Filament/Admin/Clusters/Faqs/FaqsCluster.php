<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs;

use Filament\Clusters\Cluster;

final class FaqsCluster extends Cluster
{
    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'faqs';

    public static function getNavigationGroup(): string
    {
        return 'content_management';
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.faq');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.content_management');
    }
}
