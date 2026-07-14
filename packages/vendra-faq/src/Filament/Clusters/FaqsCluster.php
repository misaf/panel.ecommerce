<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;

final class FaqsCluster extends Cluster
{
    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'faqs';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    public static function getNavigationGroup(): string
    {
        return NavigationGroup::Content->getLabel();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('vendra-faq::navigation.faq');
    }
}
