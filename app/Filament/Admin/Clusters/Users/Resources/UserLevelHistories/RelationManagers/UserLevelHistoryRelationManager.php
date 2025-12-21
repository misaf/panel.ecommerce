<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\RelationManagers;

use App\Filament\Admin\Clusters\Users\Resources\UserLevelHistories\UserLevelHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class UserLevelHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'userLevelHistories';

    public static function getModelLabel(): string
    {
        return __('user-level::navigation.user_level_history');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user-level::navigation.user_level_history');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('user-level::navigation.user_level_history');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->userLevelHistories()->count());
    }

    public function form(Schema $schema): Schema
    {
        return UserLevelHistoryResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return UserLevelHistoryResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
