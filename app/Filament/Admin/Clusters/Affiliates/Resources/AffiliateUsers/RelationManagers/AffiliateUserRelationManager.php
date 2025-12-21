<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\RelationManagers;

use App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\AffiliateUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class AffiliateUserRelationManager extends RelationManager
{
    protected static string $relationship = 'affiliateUsers';

    public static function getModelLabel(): string
    {
        return __('navigation.affiliate_user');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.affiliate_user');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.affiliate_user');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->affiliateUsers()->count());
    }

    public function form(Schema $schema): Schema
    {
        return AffiliateUserResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return AffiliateUserResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);

    }
}
