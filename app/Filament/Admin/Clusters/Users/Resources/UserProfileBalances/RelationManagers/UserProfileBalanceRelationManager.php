<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\RelationManagers;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\UserProfileBalanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class UserProfileBalanceRelationManager extends RelationManager
{
    protected static string $relationship = 'userProfileBalances';

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile_phone');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile_phone');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.user_profile_phone');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->userProfiles()->count());
    }

    public function form(Schema $schema): Schema
    {
        return UserProfileBalanceResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return UserProfileBalanceResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
