<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Permissions\Resources\Permissions\RelationManagers;

use App\Filament\Admin\Clusters\Permissions\Resources\Permissions\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class PermissionRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    public static function getModelLabel(): string
    {
        return __('navigation.permission');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.permission');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.permission');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->permissions()->count());
    }

    public function form(Schema $schema): Schema
    {
        return PermissionResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return PermissionResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
