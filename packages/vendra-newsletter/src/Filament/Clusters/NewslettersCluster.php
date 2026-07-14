<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraNewsletter\NewsletterPlugin;

final class NewslettersCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'newsletters';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    public static function getNavigationGroup(): string
    {
        return NewsletterPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter');
    }

    public static function getClusterBreadcrumb(): string
    {
        return NewsletterPlugin::get()->getNavigationGroup();
    }
}
