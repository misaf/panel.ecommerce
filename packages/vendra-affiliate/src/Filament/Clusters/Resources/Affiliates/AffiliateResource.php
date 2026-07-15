<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\CreateAffiliate;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\EditAffiliate;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\ListAffiliates;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Pages\ViewAffiliate;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\RelationManagers\ClicksRelationManager;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\RelationManagers\CommissionsRelationManager;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\RelationManagers\PayoutsRelationManager;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\RelationManagers\ReferralsRelationManager;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Schemas\AffiliateForm;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Tables\AffiliateTable;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Widgets\AffiliateClicksOverview;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Widgets\AffiliateCommissionsOverview;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\Widgets\AffiliateReferralsOverview;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraSupport\Filament\Clusters\MarketingCluster;

final class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'affiliates';

    protected static ?string $cluster = MarketingCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-affiliate::navigation.affiliate_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getRelations(): array
    {
        return [
            ReferralsRelationManager::class,
            CommissionsRelationManager::class,
            PayoutsRelationManager::class,
            ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAffiliates::route('/'),
            'create' => CreateAffiliate::route('/create'),
            'view'   => ViewAffiliate::route('/{record}'),
            'edit'   => EditAffiliate::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AffiliateClicksOverview::class,
            AffiliateReferralsOverview::class,
            AffiliateCommissionsOverview::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return AffiliateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AffiliateTable::configure($table);
    }
}
