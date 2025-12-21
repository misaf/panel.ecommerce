<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\RelationManagers;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\UserProfileDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class UserProfileDocumentRelationManager extends RelationManager
{
    protected static string $relationship = 'userProfileDocuments';

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile_document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile_document');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.user_profile_document');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->userProfileDocuments()->count());
    }

    public function form(Schema $schema): Schema
    {
        return UserProfileDocumentResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return UserProfileDocumentResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
