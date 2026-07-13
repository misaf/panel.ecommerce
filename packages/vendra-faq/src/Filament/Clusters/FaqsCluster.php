<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters;

use Filament\Clusters\Cluster;

final class FaqsCluster extends Cluster
{
    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'faqs';

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('navigation.content_management');
    }
}
