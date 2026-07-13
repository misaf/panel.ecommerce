<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Filament\Resources\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraAuthifyLog\Filament\Resources\AuthifyLogResource;

class AuthifyLogRelationManager extends RelationManager
{
    protected static string $relationship = 'authifyLogs';

    public static function getModelLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->authifyLogs()->count());
    }

    public function table(Table $table): Table
    {
        return AuthifyLogResource::table($table);
    }
}
