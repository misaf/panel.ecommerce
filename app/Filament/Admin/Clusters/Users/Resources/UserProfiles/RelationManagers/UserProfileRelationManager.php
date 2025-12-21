<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfiles\RelationManagers;

use App\Filament\Admin\Clusters\Users\Resources\UserProfiles\UserProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class UserProfileRelationManager extends RelationManager
{
    protected static string $relationship = 'userProfiles';

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.user_profile');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->userProfiles()->count());
    }

    public function form(Schema $schema): Schema
    {
        return UserProfileResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return UserProfileResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
