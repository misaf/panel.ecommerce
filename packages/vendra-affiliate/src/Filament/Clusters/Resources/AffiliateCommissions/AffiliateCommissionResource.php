<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Pages\ListAffiliateCommissions;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Pages\ViewAffiliateCommission;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Schemas\AffiliateCommissionInfolist;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Tables\AffiliateCommissionTable;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\Widgets\AffiliateCommissionOverviewWidget;
use Misaf\VendraAffiliate\Models\AffiliateCommission;
use Misaf\VendraSupport\Filament\Clusters\MarketingCluster;

use Misaf\VendraSupport\Filament\Navigation\NavigationPriority;

final class AffiliateCommissionResource extends Resource
{
    protected static ?string $model = AffiliateCommission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?int $navigationSort = NavigationPriority::AffiliateCommissions->value;

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

    public static function getNavigationLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_commissions');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_commissions');
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

    public static function infolist(Schema $schema): Schema
    {
        return AffiliateCommissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AffiliateCommissionTable::configure($table);
    }
}
