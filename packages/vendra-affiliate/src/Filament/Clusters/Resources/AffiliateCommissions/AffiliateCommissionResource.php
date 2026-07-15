<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Pages\ListAffiliateCommissions;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Pages\ViewAffiliateCommission;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Tables\AffiliateCommissionTable;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Widgets\AffiliateCommissionOverviewWidget;
use Misaf\VendraAffiliate\Models\AffiliateCommission;
use Misaf\VendraSupport\Filament\Clusters\MarketingCluster;

final class AffiliateCommissionResource extends Resource
{
    protected static ?string $model = AffiliateCommission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'affiliate-commissions';

    protected static ?string $cluster = MarketingCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-affiliate::navigation.affiliate_commission');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_commission');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-affiliate::navigation.affiliate_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_commission');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_commission');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAffiliateCommissions::route('/'),
            'view'  => ViewAffiliateCommission::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AffiliateCommissionOverviewWidget::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return AffiliateCommissionTable::configure($table);
    }
}
