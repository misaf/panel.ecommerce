<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Misaf\VendraAffiliate\Filament\Clusters\AffiliatesCluster;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Pages\ListAffiliatePayouts;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Pages\ViewAffiliatePayout;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Tables\AffiliatePayoutTable;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Widgets\AffiliatePayoutOverviewWidget;
use Misaf\VendraAffiliate\Models\AffiliatePayout;

final class AffiliatePayoutResource extends Resource
{
    protected static ?string $model = AffiliatePayout::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'affiliate-payouts';

    protected static ?string $cluster = AffiliatesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-affiliate::navigation.affiliate_payout');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_payout');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-affiliate::navigation.affiliate_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_payout');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_payout');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAffiliatePayouts::route('/'),
            'view'  => ViewAffiliatePayout::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AffiliatePayoutOverviewWidget::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return AffiliatePayoutTable::configure($table);
    }
}
