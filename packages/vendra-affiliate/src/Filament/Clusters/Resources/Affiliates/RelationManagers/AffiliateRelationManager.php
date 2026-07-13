<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\AffiliateResource;

final class AffiliateRelationManager extends RelationManager
{
    protected static string $relationship = 'affiliates';

    public static function getModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-affiliate::navigation.affiliate');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->affiliates()->count());
    }

    public function form(Schema $schema): Schema
    {
        return AffiliateResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return AffiliateResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);

    }
}
