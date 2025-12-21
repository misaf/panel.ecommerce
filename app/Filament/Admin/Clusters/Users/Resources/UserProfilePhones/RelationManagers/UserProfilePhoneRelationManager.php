<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\RelationManagers;

use App\Filament\Admin\Clusters\Users\Resources\UserProfilePhones\UserProfilePhoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class UserProfilePhoneRelationManager extends RelationManager
{
    protected static string $relationship = 'userProfilePhones';

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
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->userProfilePhones()->count());
    }

    public function form(Schema $schema): Schema
    {
        return UserProfilePhoneResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return UserProfilePhoneResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
